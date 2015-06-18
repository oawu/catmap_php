<?php echo render_cell ('frame_cell', 'header');?>

<div id='container'>

<?php
  if (isset ($message) && $message) { ?>
    <div class='info'><?php echo $message;?></div>
<?php
  } ?>
  <form action='<?php echo base_url ('pictures');?>' method='get'>
    <div class='conditions'>
      <div class='l'>
        <input type='text' name='title' value='<?php echo isset ($qs['title']) ? $qs['title'] : '';?>' placeholder='請輸入標題..' />
        <button type='submit'>尋找</button>
      </div>
      <div class='r'>
        <a class='new' href='<?php echo base_url ('pictures', 'add');?>'>新增</a>
      </div>
    </div>
  </form>

  <table class='table-list'>
    <thead>
      <tr>
        <th width='60'>ID</th>
        <th >標題</th>
        <th width='120'>斜率(h/w)</th>
        <th width='120'>圖片</th>
        <th width='150'>編輯</th>
      </tr>
    </thead>
    <tbody>
  <?php
      if ($pictures) {
        foreach ($pictures as $picture) { ?>
          <tr>
            <td><?php echo $picture->id;?></td>
            <td><?php echo $picture->title;?></td>
            <td><?php echo $picture->gradient;?></td>
            <td><?php echo img ($picture->name->url ('100w'));?></td>
            <td class='edit'>
              <a href='<?php echo base_url ('pictures', 'show', $picture->id);?>'><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="48" height="48" viewBox="0 0 48 48"><path fill="#444444" d="M17.995 24c0 3.311 2.689 6 6 6s6-2.689 6-6v-0.050c-0.641 0.65-1.52 1.050-2.5 1.050-1.931 0-3.5-1.57-3.5-3.5 0-1.39 0.819-2.6 1.99-3.16-0.62-0.22-1.29-0.34-1.99-0.34-3.31 0-6 2.69-6 6zM46.655 20.59c-3.72-4.73-12.78-12.59-22.65-12.59-9.88 0-18.949 7.86-22.67 12.59-0.84 1.080-1.3 2.25-1.33 3.41 0.030 1.16 0.49 2.33 1.33 3.41 3.721 4.731 12.78 12.59 22.66 12.59s18.939-7.859 22.66-12.59c0.85-1.080 1.31-2.25 1.34-3.41-0.030-1.16-0.49-2.33-1.34-3.41zM23.995 34c-5.52 0-10-4.48-10-10s4.48-10 10-10 10 4.48 10 10c-0 5.52-4.48 10-10 10z"></path></svg></a>
              /
              <a href='<?php echo base_url ('pictures', 'edit', $picture->id);?>'><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><path fill="#444444" d="M12 20l4-2 14-14-2-2-14 14-2 4zM9.041 27.097c-0.989-2.085-2.052-3.149-4.137-4.137l3.097-8.525 4-2.435 12-12h-6l-12 12-6 20 20-6 12-12v-6l-12 12-2.435 4z"></path></svg></a>
              /
              <a href='<?php echo base_url ('pictures', 'destroy', $picture->id);?>'><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><path fill="#444444" d="M4 10v20c0 1.1 0.9 2 2 2h18c1.1 0 2-0.9 2-2v-20h-22zM10 28h-2v-14h2v14zM14 28h-2v-14h2v14zM18 28h-2v-14h2v14zM22 28h-2v-14h2v14z"></path><path fill="#444444" d="M26.5 4h-6.5v-2.5c0-0.825-0.675-1.5-1.5-1.5h-7c-0.825 0-1.5 0.675-1.5 1.5v2.5h-6.5c-0.825 0-1.5 0.675-1.5 1.5v2.5h26v-2.5c0-0.825-0.675-1.5-1.5-1.5zM18 4h-6v-1.975h6v1.975z"></path></svg></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan='5'>目前沒有任何資料。</td></tr>
  <?php
      } ?>
    <tbody>
  </table>

<?php echo $pagination;?>

</div>

<?php echo render_cell ('frame_cell', 'footer');?>
