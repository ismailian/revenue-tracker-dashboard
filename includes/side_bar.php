<aside class="left-sidebar" data-sidebarbg="skin6">
  <a href="#" class="togside" id="togsidebar"><i id="sideicon" class="fas fa-caret-left"></i></a>
  <!-- Sidebar scroll-->
  <div class="scroll-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav">
      <ul id="sidebarnav">
        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="index.php" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a></li>
        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Applications</span></li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="products.php" aria-expanded="false"><i data-feather="tag" class="feather-icon"></i>
            <span class="hide-menu">Products </span>
          </a>
        </li>
        <li class="sidebar-item"> <a class="sidebar-link" href="calculator.php" aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span class="hide-menu">Calculator
            </span></a>
        </li>
        <li class="sidebar-item"> <a class="sidebar-link" href="reports.php" aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span class="hide-menu">Reports
            </span></a>
        </li>
        <li class="sidebar-item"> <a class="sidebar-link" href="fees.php" aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span class="hide-menu">Fees
            </span></a>
        </li>
        <li class="sidebar-item"> <a class="sidebar-link" href="settings.php" aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span class="hide-menu">Users Settings
            </span></a>
        </li>

        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Authentication</span></li>

        <?php if (!$guard->auth()) : ?>
          <li class="sidebar-item">
            <a class="sidebar-link sidebar-link" href="login.php" aria-expanded="false">
              <i data-feather="lock" class="feather-icon"></i>
              <span class="hide-menu">Login</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($guard->auth()) : ?>
          <li class="sidebar-item">
            <form action="" method="post" class="sidebar-link sidebar-link">
              <i data-feather="log-out" class="feather-icon mx-0"></i>
              <input type="submit" name="logout" value="Logout" class="btn btn-default mx-0 w-100 text-left">
            </form>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
