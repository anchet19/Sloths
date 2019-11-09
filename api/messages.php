<?php  if (count($errors) > 0) : ?>
  <div class="msg">
  	<?php foreach ($errors as $error) : ?>
  	  <span style="color:red"><?php echo $error ?></span>
  	<?php endforeach ?>
  </div>
<?php  endif ?>