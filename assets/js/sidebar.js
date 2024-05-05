<script type="text/javascript">

var sidebar = document.querySelector(".sidebar");
var closeBtn = document.querySelector("#btn");
var searchBtn = document.querySelector(".bx-search");

closeBtn.addEventListener("click", function() {
  sidebar.classList.toggle("open");
  menuBtnChange();
});

searchBtn.addEventListener("click", function() {
  sidebar.classList.toggle("open");
  menuBtnChange();
});

function menuBtnChange() {
  if (sidebar.classList.contains("open")) {
    closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
  } else {
    closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
  }
}

document.getElementById('logoutButton').addEventListener('click', function() {

  alert( 'logout clicked' );
  // Send logout request asynchronously
  fetch('../../pages/login/handlers/logout_handler.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ logout: true })
  })
  .then(response => {
      if (response.ok) {
          // Reload the page or redirect to login page upon successful logout
          window.location.reload(); // Reload the page
          // window.location.href = 'login.php'; // Redirect to login page
      } else {
          console.error('Logout failed');
      }
  })
  .catch(error => {
      console.error('Error during logout:', error);
  });
});


/*
let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn");
let searchBtn = document.querySelector(".bx-search");

closeBtn.addEventListener("click", () => {
  sidebar.classList.toggle("open");
  menuBtnChange();
});

searchBtn.addEventListener("click", () => {
  sidebar.classList.toggle("open");
  menuBtnChange();
});

function menuBtnChange() {
  if (sidebar.classList.contains("open")) {
    closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
  } else {
    closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
  }
}
*/

</script>