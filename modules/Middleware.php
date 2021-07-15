<?php
// this class restricts access to pages based on user role:
/**
 *
 */
class Middleware
{

  private $session = array();
  private $access = array();
  private $session_name = null;
  private $findBy = null;

  function __construct($session, String $userVariable)
  {
    if (isset($_SESSION[$session])) {
      $this->session = (object) $_SESSION[$session];
      $this->session_name = $session;
      if (!empty($userVariable)) {
        $this->findBy = $userVariable;
      }
    } else {
      if (!empty($userVariable)) {
        $this->session = ((object) ["{$userVariable}" => 'guest']);
        $this->findBy = $userVariable;
      }
    }
  }

  public function restrict(String $user, array $pages, String $redirectTo = "/")
  {
    array_push($this->access, [
      'username' => $user,
      'pages' => $pages,
      'redirectPage' => ($redirectTo !== '/' ? (BASE_URL . "{$redirectTo}.php") : (BASE_URL . $redirectTo)),
    ]);
  }

  private function _getAccessRole($sessionUser)
  {
    foreach ($this->access as $user) {
      if ($sessionUser === ((object) $user)->username) {
        return ((object) $user);
      }
    }
  }

  // !!! FOR DEBUGGING ONLY !!! :
  public function _access()
  {
    header('Content-Type: application/json');
    $sessionUser = $this->session->{$this->findBy};
    print_r([
      'Logged In User : ' . $sessionUser,
      'Logged In User : ' . json_encode(($this->_getAccessRole($sessionUser))->pages),
      'Redirect Page  : ' . ($this->_getAccessRole($sessionUser))->redirectPage,
    ]);
    exit();
  }

  // !!! FOR DEBUGGING ONLY !!! :
  public function _session()
  {
    header('Content-Type: application/json');
    print_r($this->session);
    exit();
  }

  public function current_session()
  {
    return $this->session;
  }

  private function _monitor()
  {
    $sessionUser = $this->session->{$this->findBy};
    $scriptFile = (object) pathinfo(((object) $_SERVER)->SCRIPT_FILENAME);
    if (in_array($scriptFile->filename, $this->_getAccessRole($sessionUser)->pages)) {
      return True;
    }
    return False;
  }

  private function run()
  {
    if (!$this->_monitor()) {
      $this->_redirect();
    }
  }

  // redirect unauthorized access to their rightfull place:
  private function _redirect()
  {
    $sessionUser = $this->session->{$this->findBy};
    $redirectTo = $this->_getAccessRole($sessionUser)->redirectPage;
    header("location: " . $redirectTo);
  }

  // handle guests Intrusion:
  public function watch()
  {
    $this->run();
  }

  // check if authenticated:
  public function auth()
  {
    if (!is_null($this->session_name) && isset($_SESSION[$this->session_name])) {
      return true;
    }
    return false;
  }

  function __destruct()
  {
    if (!empty($this->session)) {
      if (!empty($this->findBy)) {
        $this->run();
      }
    }
  }

  // update the session upon an update:
  public function refresh()
  {
    if (isset($_SESSION[$this->session_name])) {
      $this->session = $_SESSION[$this->session_name];
    }
  }
}
