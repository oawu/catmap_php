<?php echo render_cell ('frame_cell', 'header');?>

<div id='container'>


  <table class='table-form'>
    <tbody>
      <tr>
        <th>名稱</th>
        <td>
          <?php echo $picture->title;?>
        </td>
      </tr>
      <tr>
        <th>圖片</th>
        <td>
          <?php echo img ($picture->name->url ('800w'));?>
        </td>
      </tr>
      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('pictures');?>'>回列表</a>
        </td>
      </tr>
    </tbody>
  </table>

</div>

<?php echo render_cell ('frame_cell', 'footer');?>
