<?php
	if(session_id() == '' || session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
  include "../inc/function.php";
  
  if (!isset($_SESSION["username"])) {
        moveTo("../index.html");
  }
  $isAdmin = isAdmin($_SESSION["username"]);
  
  
?>
<!DOCTYPE html>
<html lang="ko-KR" dir="ltr">
  <head>
    <title>KMS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1000, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Oxygen:400,700">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/layout.css">
      <script>
      
     
        var isAdmin = '<?php echo $isAdmin; ?>';
      
    </script>

    <script charset="utf-8" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!--<script charset="utf-8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>-->
    <script charset="utf-8" src="../js/jquery.dataTables.js"></script>
    <script charset="utf-8" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>

    <script src="../js/ckeditor/ckeditor.js"></script>
    <script charset="utf-8" src="../js/webapp.js"></script>

  </head>
  <body>
    <div id="page_container">
      <h1>Unicity Korea - KMS</h1>
      <?php if($isAdmin == "1"){ ?>
      <button type="button" class="button" id="add_qna">추가하기</button>
      <?php } ?>

      <table class="datatable" id="table_qnas">
        <tfoot>
            <tr>
                <th>필터</th>
                <th>type1</th>
                <th>type2</th>
            </tr>
        </tfoot>
        <thead>
          <tr>
            <th>번호</th>
            <th>대분류</th>
            <th>소분류</th>
            <th>제목</th>
            <th>글쓴이</th> 
            <th>내용</th> 
            <th>날짜</th>
             <th> </th>
          </tr>
        </thead>
        <tbody>
        </tbody>
         
      </table>

    </div>

    <div class="lightbox_bg"></div>

    <div class="lightbox_container">
      <div class="lightbox_close"></div>
      <div class="lightbox_content">
        
        <h2>QnA 생성하기</h2>
        <form class="form add" id="form_qna" enctype="multipart/form-data" data-id="" novalidate>
          <div class="input_container">
            <label for="type1">대분류: <span class="required">*</span></label>
            <div class="field_container">
            	<select name="type1" id="type1" class="text" required>
               
               	</select>
            </div>
          </div>
          <div class="input_container">
            <label for="type2">소분류: <span class="required">*</span></label>
            <div class="field_container">
            	<select name="type2" id="type2" required>
                
               	</select>
            </div>
          </div>
          <div class="input_container">
            <label for="title">제 목: <span class="required">*</span></label>
            <div class="field_container">
              <input type="text"  name="title" id="title" value="" required>
            </div>
          </div>
          <div class="input_container">
            <label for="description">내 용: <span class="required">*</span></label>
            <div class="field_container">
              <textarea name="description" id="description" cols="800" value="" required> </textarea>
            	<script>
            		CKEDITOR.replace('description', {
            			language: 'ko',
            			width: '750' 
            			//, colorButton_enableAutomatic: false  
					 	//, colorButton_colors: 'CF5D4E,454545,FFF,CCC,DDD,CCEAEE,66AB16'
            			 
            		});
        		</script>
            </div>
          </div>
          <div class="input_container">
            <label for="addFile">파 일: </label>
            <div class="field_container">
              <div name="addFile" id="addFile">파일추가</div>
              <!-- <div id="fileUploader"></div> -->
              <input type="file" name="files" id ="files" multiple>
              <input type="hidden" name="writer" id="writer" value="<?php echo $_SESSION['username'];?>">
            </div>
          </div>
          <div class="button_container">
            <button type="submit" id="addNew">Add</button>
          </div>
        </form>
        
      </div>
    </div>

    <noscript id="noscript_container">
      <div id="noscript" class="error">
        <p>JavaScript support is needed to use this page.</p>
      </div>
    </noscript>

    <div id="message_container">
      <div id="message" class="success">
        <p>This is a success message.</p>
      </div>
    </div>

    <div id="loading_container">
      <div id="loading_container2">
        <div id="loading_container3">
          <div id="loading_container4">
            Processing, please wait...
          </div>
        </div>
      </div>
    </div>

  </body>
</html>