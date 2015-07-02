<?php echo render_cell ('admin_frame_cell', 'header');?>

<div id='container'>


  <table class='table-form'>
    <tbody>
      <tr>
        <th>會員</th>
        <td>
          <?php echo $picture->user->name;?>(<?php echo $picture->user->id;?>)
        </td>
      </tr>
      <tr>
        <th>描述</th>
        <td style='word-break:break-all;'>
          <?php echo $picture->description;?>
        </td>
      </tr>
      <tr>
        <th>斜率(h/w)</th>
        <td>
          <?php echo $picture->gradient;?>
        </td>
      </tr>
      <tr>
        <th>城市</th>
        <td>
          <?php echo $picture->city;?>
        </td>
      </tr>
      <tr>
        <th>國家</th>
        <td>
          <?php echo $picture->country;?>
        </td>
      </tr>
      <tr>
        <th>地址</th>
        <td>
          <?php echo $picture->address;?>
        </td>
      </tr>
      <tr>
        <th>照片</th>
        <td>
          <?php echo img ($picture->name->url ('800w'));?>
        </td>
      </tr>
      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'pictures');?>'>回列表</a>
        </td>
      </tr>
    </tbody>
  </table>

</div>

<?php echo render_cell ('admin_frame_cell', 'footer');?>
