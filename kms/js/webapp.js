/* Formatting function for row details - modify as you need */
function format ( d ) {
    var files ="";
    var html="";
    if (typeof d.filename !== "undefined" && d.filename !== null){
      files = d.filename.split(";");
       
      for(i=0;i<files.length;i++){
        if (files[i] !== "") {
          html +='<tr><td style="border:0;">';
          html += '<a href="https://www.makelifebetter.co.kr/kms/uploads/'+files[i]+'" target="_blank">'+files[i]+'</a>';
          html += '</td></tr>';
        }
      }
    }
    // `d` is the original data object for the row
    description = d.description.replace(/\\/g,"");
  
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;border:0;width:100%;"  >'+
        '<tr>'+
            '<td style="border:0;background-color:#CEE3F6;">'+ description+'</td>'+
        '</tr>'+html +
        
    '</table>';
}

$(document).ready(function(){

  // Variable to store your files
  var files;
  var type1Items = [ 
    {"value": 1, "text": '회원십'},
 //   {"value": 2, "text": '제품'},
 //   {"value": 3, "text": '수당'},
 //   {"value": 4, "text": '국제후원'},
    {"value": 5, "text": '주문'},
//    {"value": 6, "text": '반품/교환'},
    {"value": 9, "text": '기타'}
  ];

  var type2Items = [ 
    {"value": 101, "text": '회원가입'},
    {"value": 102, "text": '정보변경'},
    {"value": 103, "text": '해지'},
    {"value": 104, "text": '갱신'},
    {"value": 105, "text": '공동등록'},
    {"value": 106, "text": '주부사업자 전환'},
  //  {"value": 107, "text": '정보변경'},
 //   {"value": 107, "text": '후원자변경'},  107로 합침
 //   {"value": 108, "text": '국적변경'},   107로 합침
    {"value": 109, "text": '상속'},
 //   {"value": 110, "text": '진정서'},
 //   {"value": 111, "text": '회원간 결혼'}, 107로 합침
 //   {"value": 112, "text": '사업자 연결'},
 //   {"value": 113, "text": 'HUB 불러오기'},
 //   {"value": 114, "text": '위임장'},
    {"value": 115, "text": '기타'},
    {"value": 501, "text": '택배/배송'},
    {"value": 502, "text": '주문관련'},
  //  {"value": 502, "text": '주문자 변경'}, 502로 합침
  //  {"value": 503, "text": '주문취소'},   502로 합침
    {"value": 504, "text": '카드관련'},
    {"value": 505, "text": '오토십'},
    {"value": 506, "text": '마감관련'},
    {"value": 507, "text": '반품/교환'}, 
  //  {"value": 506, "text": '일일마감'},  506으로 합침
  //  {"value": 507, "text": '총마감'},    506으로 합침
    {"value": 508, "text": '기타'},
    {"value": 901, "text": '국제후원'},
  //  {"value": 902, "text": 'PD이하 핀수령'},
    {"value": 903, "text": '정수기'},
    {"value": 904, "text": '온라인'},
    {"value": 905, "text": '쇼핑몰'},
    {"value": 906, "text": '온라인판매'},
    {"value": 907, "text": '수당관련'},
    {"value": 908, "text": '전산'},
  //  {"value": 909, "text": '상품권 접수'},
    {"value": 910, "text": '세무관련'},
    {"value": 911, "text": '기타'}
  ];
  $.each(type1Items, function (i, item) {
    $('#type1').append($('<option>', { 
        value: item.value,
        text : item.text 
    }));
  });

  $('#type1').change(function() {
     
    var type1Value = $("#type1").val();
     
    $('#type2').empty();

    $.each(type2Items, function (i, item) {
      if (item.value >= (type1Value*100) && item.value < ((type1Value*100)+100)) {
        $('#type2').append($('<option>', { 
            value: item.value,
            text : item.text 
        }));
      }
    });
  });
  var counter = 0;
  // On page load: datatable
  var table_qnas = $('#table_qnas').DataTable({
    initComplete: function () {
      this.api().columns().every(function() {
                 
        if (counter == 0) {
          counter +=1;
        } else {    
          var column = this;
          var select = $('<select><option value=""></option></select>')
          .appendTo( $(column.footer()).empty() )
          .on('change', function () {
            var val = $.fn.dataTable.util.escapeRegex(
              $(this).val()
            );
            column
            .search( val ? '^'+val+'$' : '', true, false )
            .draw();
          });
         
          column.data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
          }); 
        }
      });
    },
    
    "ajax": {
        url: "https://www.makelifebetter.co.kr/kms/inc/server-response.php?job=get_qnas"
    },
    "columns": [
      { "data": "id" },
      { "data": "type1" },
      { "data": "type2" },
      { "data": "title",   "sClass": "qna_name" },
      { "data": "writer", "sClass": "writer_name" },
      { "data": "description" },
      { "data": "timestamp",   "sClass": "qna_date" } ,
      { "data": "functions",      "sClass": "functions" }
      
    ],
     
    "aoColumnDefs": [
      { "targets": [ 5 ], "visible": false} 
      , {"bSortable": false, "aTargets": [-1] }
      
    ],

    "order": [[ 0, "desc" ]],
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       " ",
        "sPrevious":    " ",
        "sNext":        " ",
        "sLast":        " ",
      },
      "sLengthMenu":    "페이지당 건 수: _MENU_",
      "sInfo":          "총 _TOTAL_ 건 (_START_ / _END_)",
      "sInfoFiltered":  "(filtered from _MAX_ total records)"
    },

  });
  //var column = table_qnas.column($(this).attr(''));
 var column = table_qnas.column(7);
  
  console.log(column);
  if(isAdmin != "1"){
    column.visible(false);
  }
  
 
  
  // On page load: form validation
  jQuery.validator.setDefaults({
    success: 'valid',
    rules: {
      title: {
          required: true,
          minlength: 2,
          maxlength: 100
        }
    },
    errorPlacement: function(error, element){
      error.insertBefore(element);
    },
    highlight: function(element){
      $(element).parent('.field_container').removeClass('valid').addClass('error');
    },
    unhighlight: function(element){
      $(element).parent('.field_container').addClass('valid').removeClass('error');
    }
  });


    // Add event listener for opening and closing details
    $('#table_qnas tbody').on('click', 'td', function () {
        if($(event.target).hasClass('qna_name')) {
          var tr = $(this).closest('tr');
          var row = table_qnas.row( tr );
          
          if ( row.child.isShown() ) {
            // This row is already open - close it
            $(event.target).css( {"font-weight":"normal","color":"black"});
            row.child.hide();
            tr.removeClass('shown');
          } else {
            // Open this row
            
            $(event.target).css({"font-weight":"bold","color":"red"});
            row.child(format(row.data())).show();
            tr.addClass('shown');
          }
        }
        
    } );
 
  var form_qna = $('#form_qna');
  form_qna.validate();


  // Show message
  function show_message(message_text, message_type){
    $('#message').html('<p>' + message_text + '</p>').attr('class', message_type);
    $('#message_container').show();
    if (typeof timeout_message !== 'undefined'){
      window.clearTimeout(timeout_message);
    }
    timeout_message = setTimeout(function(){
      hide_message();
    }, 8000);
  }
  // Hide message
  function hide_message(){
    $('#message').html('').attr('class', '');
    $('#message_container').hide();
  }

  // Show loading message
  function show_loading_message(){
    $('#loading_container').show();
  }
  // Hide loading message
  function hide_loading_message(){
    $('#loading_container').hide();
  }

  // Show lightbox
  function show_lightbox(){
    $('.lightbox_bg').show();
    $('.lightbox_container').show();
  }
  // Hide lightbox
  function hide_lightbox(){
    $('.lightbox_bg').hide();
    $('.lightbox_container').hide();
  }
  // Lightbox background
  $(document).on('click', '.lightbox_bg', function(){
    hide_lightbox();
  });
  // Lightbox close button
  $(document).on('click', '.lightbox_close', function(){
    hide_lightbox();
  });
  // Escape keyboard key
  $(document).keyup(function(e){
    if (e.keyCode == 27){
      hide_lightbox();
    }
  });
  
  // Hide iPad keyboard
  function hide_ipad_keyboard(){
    document.activeElement.blur();
    $('input').blur();
  }

 $('input[type=file]').on('change', prepareUpload);

  // Grab the files and set them to our variable
  function prepareUpload(event)
  {
    console.log(event);
    files = event.target.files;
    console.log(files);
  }
  // Add company button
  $(document).on('click', '#add_qna', function(e){

    e.preventDefault();
    $('.lightbox_content h2').text('추가하기');
    $('#form_qna #addNew').text('추가하기');
    $('#form_qna').attr('class', 'form add');
    $('#form_qna').attr('data-id', '');
    $('#form_qna .field_container label.error').hide();
    $('#form_qna .field_container').removeClass('valid').removeClass('error');
    $('#form_qna #type1').val('');
    $('#form_qna #type2').val('');
    $('#form_qna #title').val('');
    $('#form_qna #description').val('');
    CKEDITOR.instances.description.setData('');
//    $('#form_qna #filename').val('');
    $('#form_qna #filename').val();
    show_lightbox();
  });

/* $(document).on('click', '#addFile', function() {
    var x = document.createElement("INPUT");
    x.setAttribute("type", "file");
    x.setAttribute("name", "files");
    $('#fileUploader').append(x);
  });*/

  // Add company submit form
  $(document).on('submit', '#form_qna.add', function(e){
    e.preventDefault();
    // Validate form
    if (form_qna.valid() == true){
      // Send company information to database
      var filename= "";
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
    
     if(typeof files !== 'undefined' ){
        uploadFiles();
        for(var i = 0;i<files.length;i++){
          filename += files[i].name+";"; // we can put more than 1 image file
        }
        // filename = "&files="+filename;
      }
      
      //var form_data = $('#form_qna').serialize() + filename;
      var form = $('form')[0];
      var form_data = new FormData(form);
      form_data.append('files', filename);
      form_data.set('description', CKEDITOR.instances.description.getData());
       
      var request   = $.ajax({
        url:          '../inc/server-response.php?job=add_qna',
        cache:        false,
        data:         form_data,
        processData:  false,
        contentType:  false,
        type:         'POST'
      });
      request.done(function(output){
        output = JSON.parse(output);
        console.log(output.result);
        if (output.result == 'success'){
          // Reload datable
          table_qnas.ajax.reload(function(){
            hide_loading_message();
            show_message("저장완료", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('저장실패', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('저장 실패: ' + textStatus, 'error');
      });
    }
  });

  // Edit button
  $(document).on('click', '.function_edit a', function(e){
    e.preventDefault();
    // Get company information from database
    show_loading_message();
    $('#form_qna #filename').val();
    var id      = $(this).data('id');

    var request = $.ajax({
      url:          '../inc/server-response.php?job=get_qna',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){

      if (output.result == 'success'){
        $('.lightbox_content h2').text('수정하기');
        $('#form_qna #addNew').text('수정하기');
        $('#form_qna').attr('class', 'form edit');
        $('#form_qna').attr('data-id', id);
        $('#form_qna .field_container label.error').hide();
        $('#form_qna .field_container').removeClass('valid').removeClass('error');
        $('#form_qna #type1').val(output.data[0].type1);
        $('#form_qna #type2').val(output.data[0].type2);
        $('#form_qna #title').val(output.data[0].title);
        CKEDITOR.instances.description.setData(output.data[0].description);
        $('#form_qna #filename').val(output.data[0].filename);
        
        hide_loading_message();
        show_lightbox();
      } else {
        hide_loading_message();
        show_message('Information request failed', 'error');
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message('Information request failed: ' + textStatus, 'error');
    });
  });
  
  // Edit company submit form
  $(document).on('submit', '#form_qna.edit', function(e){
    e.preventDefault();
    var filename= "";
    // Validate form
    if (form_qna.valid() == true){
      // Send company information to database
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();

     if(typeof files !== 'undefined' ){
        uploadFiles();
        for(var i = 0;i<files.length;i++){
          filename += files[i].name+";"; // we can put more than 1 image file
        }
     //   filename = "&files="+filename;
      }

      var id        = $('#form_qna').attr('data-id');
  
      var form = $('form')[0];
      var form_data = new FormData(form);
      form_data.append('files', filename);
      form_data.append('id', id);
      
      var request  = $.ajax({
        url:          '../inc/server-response.php?job=edit_qna',
        cache:        false,
        data:         form_data,
        processData:  false,
        contentType:  false,
        type:         'POST'
      });
      request.done(function(output){
        output = JSON.parse(output);
        if (output.result == 'success'){
          // Reload datable
         
          table_qnas.ajax.reload(function(){
            hide_loading_message();
            var title = $('#title').val();
            show_message("수정 완료", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('수정 실패', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('수정 실패: ' + textStatus, 'error');
      });
    }
  });
  
  // Delete company
  $(document).on('click', '.function_delete a', function(e){
    e.preventDefault();
    var company_name = $(this).data('name');
    if (confirm("Are you sure you want to delete '" + company_name + "'?")){
      show_loading_message();
      var id      = $(this).data('id');
      var request = $.ajax({
        url:          '../inc/server-response.php?job=delete_qna&id=' + id,
        cache:        false,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          // Reload datable
          table_qnas.ajax.reload(function(){
            hide_loading_message();
            show_message("Title '" + company_name + "' deleted successfully.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Delete request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Delete request failed: ' + textStatus, 'error');
      });
    }
  });

  function uploadFiles( )
  {
    console.log('upload Start');
    // Create a formdata object and add the files
    var data = new FormData();
    var c = 0;
    var file_data, arr;
    console.log(c);
    $('input[type=file]').each(function() {
      
      file_data = $('input[type="file"]')[c].files; // get multiple files from input file
      
      for(var i = 0;i<file_data.length;i++){
        data.append('arr[]', file_data[i]); // we can put more than 1 image file
      }
      c++;
    });
     console.log('prepare Start');
    $.ajax({
        url: 'https://www.makelifebetter.co.kr/kms/inc/upload.php',
        type: 'POST',
        data: data,
        cache: false,
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data)
        {
            if(typeof data.error === 'undefined')
            {
              console.log(data);
                // Success so call function to process the form
                //submitForm(event, data);
                return true;
            }
            else
            {
                // Handle errors here
                console.log('ERRORS: ' + data.error);
                return false;
              //  hide_loading_message();
              //  show_message('파일 업로드 실패' 'error');
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
           // hide_loading_message();
           // show_message('파일 업로드 실패: ' + textStatus, 'error');
            // STOP LOADING SPINNER
            return false;
        }
    });
  }

});