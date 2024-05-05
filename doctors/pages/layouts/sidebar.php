<?php if( isset( $_COOKIE['user_id'] ) && !empty( $_COOKIE['user_id'] ) && isset( $_COOKIE['user_type'] ) && !empty( $_COOKIE['user_type'] ) ) { $user_id = $_COOKIE['user_id']; $user_type = $_COOKIE['user_type']; } ?>

<div class="sidebar">
    <div class="logo-details">
      <i class='bx bxl-codepen icon'></i>
      <div class="logo_name">SideMenu</div>
      <i class='bx bx-menu' id="btn"></i>
    </div>
    <ul class="nav-list">
      <li>
        <a href="http://kidneyhealthmonitor.free.nf/doctors/pages/profile">
          <i class='bx bx-user'></i>
          <span class="links_name">Profile</span>
        </a>
        <span class="tooltip">Profile</span>
      </li>

        <li>
        <a href="http://kidneyhealthmonitor.free.nf/doctors/pages/patients/patients_list.php">
          <i class='bx bx-user'></i>
          <span class="links_name">Patient List</span>
        </a>
        <span class="tooltip">Patient List</span>
      </li>

    </ul>
  </div>