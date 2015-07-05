<div class='picture'>
  <?php
    if ($picture->has_color ()) { ?>
      <div class='background' style='background-color: rgba(<?php echo $picture->color_red;?>, <?php echo $picture->color_green;?>, <?php echo $picture->color_blue;?>, 1);'></div>
  <?php
    }
  ?>

  <div class='img'>
    <img src='<?php echo $picture->name->url ();?>' />
  </div>
  <div class='user'>
    <div class='avatar'>
      <img src='<?php echo $picture->user->avatar->url ('65x65t');?>' />
    </div>
    <div class='info'>
      <div class='name'><?php echo $picture->user->name;?></div>
      <div class='sub_item'>
        <div class='created_at timeago' data-time="<?php echo $picture->user->created_at;?>"><?php echo $picture->user->created_at;?></div>
        <div class='city'><?php echo $picture->city;?></div>
      </div>
    </div>
  </div>
  <div class='introduction'><?php echo $picture->description;?></div>
  <div class='bottom'>
    <div class='comment'><?php echo count ($picture->comments);?>則留言</div>
    <div class='like'>+<?php echo count ($picture->likes);?>個最愛</div>
  </div>
</div>
<!-- • -->