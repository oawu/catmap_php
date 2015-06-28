<div class='picture'>
  <div class='img'>
    <img src='<?php echo $picture->name->url ();?>' />
  </div>
  <div class='user'>
    <div class='avatar'>
      <img src='<?php echo $picture->user->avatar->url ();?>' />
    </div>
    <div class='info'>
      <div class='name'><?php echo $picture->user->name;?></div>
      <div class='created_at timeago' data-time="<?php echo $picture->user->created_at;?>"><?php echo $picture->user->created_at;?></div>
    </div>
  </div>
  <div class='introduction'><?php echo $picture->description;?></div>
  <div class='bottom'>
    <div class='comment'>留言 <?php echo count ($picture->likes);?></div>
    <div class='like'>人氣 <?php echo count ($picture->comments);?></div>
  </div>
</div>