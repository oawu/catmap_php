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
        <th>色系</th>
        <td>
          <div class='color' style='background-color: rgba(<?php echo $picture->color_red;?>, <?php echo $picture->color_green;?>, <?php echo $picture->color_blue;?>, 1);'></div>
        </td>
      </tr>
      <tr>
        <th>緯經度</th>
        <td>
          緯度：<?php echo $picture->latitude;?><br/>
          經度：<?php echo $picture->longitude;?><br/>
          海拔：<?php echo $picture->altitude;?>
        </td>
      </tr>
      <tr>
        <th>準確度</th>
        <td>
          平面：<?php echo $picture->accuracy_horizontal;?><br/>
          高度：<?php echo $picture->accuracy_vertical;?><br/>
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
