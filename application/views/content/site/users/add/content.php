<?php echo render_cell ('frame_cell', 'header');?>

<div id='container'>

<?php
    if (isset ($message) && $message) { ?>
      <div class='error'><?php echo $message;?></div>
<?php
    } ?>

    <form action='<?php echo base_url (array ('users', 'create'));?>' method='post' enctype='multipart/form-data'>
      <table class='table-form'>
        <tbody>
          <tr>
            <th>暱稱</th>
            <td>
              <input type='text' name='name' value='<?php echo $name;?>' placeholder='請輸入暱稱..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
            </td>
          </tr>
          <tr>
            <th>帳號</th>
            <td>
              <input type='text' name='account' value='<?php echo $account;?>' placeholder='請輸入帳號..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
            </td>
          </tr>
          <tr>
            <th>密碼</th>
            <td>
              <input type='text' name='password' value='<?php echo $password;?>' placeholder='請輸入密碼..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
            </td>
          </tr>
          <tr>
            <td colspan='2'>
              <a href='<?php echo base_url ('users');?>'>回列表</a>
              <button type='reset' class='button'>重填</button>
              <button type='submit' class='button'>確定</button>
            </td>
          </tr>
        </tbody>
      </table>
    </form>

</div>

<?php echo render_cell ('frame_cell', 'footer');?>
