<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo e($logo->title ?? ''); ?></title>
<link rel="icon" type="image/x-icon" href="<?php echo e(url('/')); ?>/<?php echo e($settings->logo ?? ''); ?>">
<style>

form {border: 3px solid #f1f1f1;
    box-shadow: 2px 2px 2px 2px #413c69;
    padding: 5px;}

input[type=email], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  color: white;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
}

img.avatar {
  width: 70px;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media  screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>


<div style="width: 320px;
    margin: 0px auto;
    margin-top: 10vh;
    border-radius: 10px;
    background-color: #ffffff;
    ">
   
    <h2 style="text-align:center"><?php echo e($settings->title ?? ''); ?></h2>
<form method="POST" action="<?php echo route('login'); ?>">
                        <?php echo csrf_field(); ?>
  <div class="imgcontainer">
    <a href="<?php echo e(url('/')); ?>">
        <img src="<?php echo e($settings->logo ?? ''); ?>" alt="Avatar" class="avatar">
    </a>
  </div>
 <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div class="container">
    <label for="email"><b> <?php echo __('E-Mail Address'); ?></b></label>
    <input type="email" placeholder="Enter email" name="email" required value="<?php echo old('email'); ?>">

    <label for="password"><b><?php echo __('Password'); ?></b></label>
    <input type="password" placeholder="Enter Password" name="password" required>
        
    <button type="submit"><img src="https://img.icons8.com/ios-filled/50/000000/login-rounded-right.png"/></i>
 </button>
    
  </div>

</form>
</div>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\own\sabuz-bhai\sspf.sobuzsathitraders.com\sspf.sobuzsathitraders.com\resources\views/auth/login.blade.php ENDPATH**/ ?>