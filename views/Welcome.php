<?php

namespace MEM\prjMitralPHP;

// Page object
$Welcome = &$Page;
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="dialog.css"> 
<style>
/* The modal (background) */
.modal {
  /* position: fixed;  */
  z-index: 1; /* Sit on top */
  padding-top: 140px; /* Location of the box */
  left: 0;
  top: 0;
   height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  /* background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0);  */
}

/* modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  border-radius: 25px;
  width:600px;
  padding: 0;
  border: 1px solid #888;
  box-shadow: 4px 4px 8px 4px rgba(0,0,0,0.2),4px 6px 20px 4px rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}


.modal-header {
  padding: 20px 0px 20px;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}
  </style>
</head>
<body>

<!-- Trigger/Open The Modal -->
<!-- The Modal -->
<div id="myModal" class="modal" style="display: block;">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <img src="https://i.ibb.co/r0Nygs9/logo-Mitral2.jpg">
    </div>
    <div class="modal-body">
      <p style="text-align:center;"><img src="https://i.ibb.co/Y20LXnR/item-header.png" width="200" height="200"></p>
      <!-- <p style="text-align:center;">Nama : <b>Moh Endi Nugroho</b></p>-->
      <p style="text-align:center;">Aplikasi : <b>Mitral Employee Management (MEM)</b></p>
    </div>
  </div>

</div>
</body>
</html>


<?php
echo GetDebugMessage();
?>
