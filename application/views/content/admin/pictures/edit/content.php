<?php echo render_cell ('admin_frame_cell', 'header');?>

<div id='container'>

<?php
    if (isset ($message) && $message) { ?>
      <div class='error'><?php echo $message;?></div>
<?php
    } ?>

    <form action='<?php echo base_url ('admin', 'pictures', 'update', $picture->id);?>' method='post' enctype='multipart/form-data'>
      <table class='table-form'>
        <tbody>
          <tr>
            <th>會員</th>
            <td>
        <?php if ($users = User::all ()) { ?>
                <select name='user_id'>
            <?php foreach ($users as $user) { ?>
                    <option value='<?php echo $user->id;?>'<?php echo ($user_id ? $user_id : $picture->user->id) == $user->id ? "selected" : "";?>><?php echo $user->name;?></option>
            <?php } ?>
                </select>
        <?php }?>
            </td>
          </tr>
          <tr>
            <th>名稱</th>
            <td>
              <textarea name='description' placeholder='請輸入描述..'><?php echo $description ? $description : $picture->description;?></textarea>
            </td>
          </tr>
          <tr>
            <th>照片</th>
            <td>
              <?php echo img ($picture->name->url ('100w'));?>
              <hr/>
              <input type='file' name='name' value='' placeholder='請選擇照片..'accept="image/gif, image/jpeg, image/png"/>
            </td>
          </tr>
          <tr>
            <th>城市</th>
            <td>
              <select name='city'>
          <?php foreach ($cities as $city) { ?>
                  <option value='<?php echo $f_city ? $f_city : $picture->city;?>'<?php echo ($f_city ? $f_city : $picture->city) == $city ? "selected" : "";?>><?php echo $city;?></option>
          <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <th>國家</th>
            <td>
              <input type='text' name='country' value='<?php echo $country ? $country : $picture->country;?>' placeholder='請輸入國家..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
            </td>
          </tr>
          <tr>
            <th>地址</th>
            <td>
              <input type='text' name='address' value='<?php echo $address ? $address : $picture->address;?>' placeholder='請輸入地址..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
            </td>
          </tr>
          <tr>
            <td colspan='2'>
              <a href='<?php echo base_url ('admin', 'pictures');?>'>回列表</a>
              <button type='reset' class='button'>重填</button>
              <button type='submit' class='button'>確定</button>
            </td>
          </tr>
        </tbody>
      </table>
    </form>

</div>

<?php echo render_cell ('admin_frame_cell', 'footer');?>
