



$(document).on('click', '.add-sub', function () { 

    var elmt = $("#add-sub-dumy").clone();
    var nm = $("#sub-list .row").length + 1;
    elmt.attr('id','');
    elmt.find('input').val('');
    elmt.find('select').attr('name','sub['+nm+'][label]');
    elmt.find('input').attr('name','sub['+nm+'][file]');
    elmt.find('.remove-sub').removeClass('d-none');

    elmt.find('.sub-label, .sub-file, .download, .is_remove_sub').remove(); 

    $("#sub-list").append(elmt);

});


$(document).on('click', '.remove-sub', function () { 
    if($(this).parent().parent().find('.is_remove_sub').length > 0)
    {
        $(this).parent().parent().addClass('d-none');
        $(this).parent().parent().find('.is_remove_sub').val(1);
    }
    else
    {
        $(this).parent().parent().remove();
    }

    

});

$(".remove-preview-img").on('click', function(){
    $(".preview-img-wrap").append('<input type="text" name="pre_img_del" hidden >');
    $(".preview-img-wrap").addClass('d-none');
});


$(".edit-server").on('click', function(){

    $('#form-server').trigger("reset");

    var elmt = $(this).parent().parent().parent();
    var id = elmt.attr('data-id');
    var name = elmt.find('.server-name').text();
    var domain = elmt.find('.server-domain').text();
    
    $("#server-id").val(id);
    $("#server-name").val(name);
    $("#server-domain").val(domain);

});

$(".del-server").on('click', function(){
    
    var link = $(this).attr('data-url');
    $("#del-link").attr('href',link);
    $("#del-confirm .ctxt").text('server');
    $("#del-confirm").modal('show');

});

$(".del-gauth").on('click', function(){
    
    var link = $(this).attr('data-url');
    $("#del-link").attr('href',link);
    $("#del-confirm .ctxt").text('drive account');
    $("#del-confirm").modal('show');

});


$(".refresh-server").on('click', function(){
    
    var elmt = $(this).parent().parent().parent();
    var id = elmt.attr('data-id');

    if(!$(this).hasClass('spin'))
    {
        $(this).addClass('spin');
    }
    var $this = $(this);

    $.ajax({
        type: "GET",
        url: PROOT + '/ajax',
        data: 'id='+id+'&type=refresh-server',
        cache: false,
        success: function (data) {
            console.log(data);
            if(data.success)
            {
                displayAlert(' <b>Server status updated -> SUCCESS !</b>', 'success');
            }
            else
            {
                displayAlert(' <b>Server status updated -> FAILED !</b>', 'danger');
                // displayAlert(' <b>GDplyr is up to date :)</b>', 'success');
            }
            $this.removeClass('spin');
        },
        error: function (xhr) { // if error occured
            alert("Error occured.please try again");
            $this.removeClass('spin');
           
        }
    });
    

});
console.log("%cFUCK YOU!", "color: red;font-size:128px; font-weight:bold"); 


$(".refresh-gauth").on('click', function(){
    
    var id = $(this).attr('data-id');

    if(!$(this).hasClass('spin'))
    {
        $(this).addClass('spin');
    }
    var $this = $(this);

    $.ajax({
        type: "GET",
        url: PROOT + '/ajax',
        data: 'id='+id+'&type=refresh-gauth',
        cache: false,
        success: function (data) {
            console.log(data);
            if(data.success)
            {
                if(!$('.sd-'+id).hasClass('bg-green-lt'))
                {
                    $('.sd-'+id).removeClass('bg-red-lt');
                    $('.sd-'+id).addClass('bg-green-lt');
                    $('.sd-'+id).text('Active');
                }

                displayAlert(' <b>GDrive auth status updated -> SUCCESS !</b>', 'success');
            }
            else
            {
                if(!$('.sd-'+id).hasClass('bg-red-lt'))
                {
                    $('.sd-'+id).removeClass('bg-green-lt');
                    $('.sd-'+id).addClass('bg-red-lt');
                    $('.sd-'+id).text('Broken');
                }
                displayAlert(' <b>GDrive auth status updated -> FAILED !</b>', 'danger');
            }
            $this.removeClass('spin');
        },
        error: function (xhr) { // if error occured
            alert("Error occured.please try again");
            $this.removeClass('spin');
           
        }
    });
    

});





function displayAlert(msg , type)
{
  
                              
                          
  var html = '<div class="alert alert-'+type+' dismissible-alert" role="alert">';

  html += msg +'  <i class="alert-close mdi mdi-close"></i>';

  html += '</div>  ';

  $("#alert-wrap").html(html);

}





   $( function() {
    $( "#alt-link-list, #sub-list" ).sortable({
        handle: ".move",
    });
    // $( "#alt-link-list, #sub-list" ).disableSelection();
  } );


  
$(document).on('click', '.copy-plyr-link2', function () {
    var $this = $(this);
    $this.text('copied');
    var url = $(this).parent().find('.plyr-link').text();
    copyToClipboard(url);

    setTimeout(function() { 
        var t = 'copy';
        $this.text(t);
    }, 2000);

});


$(document).on('click', '.copy-plyr-link', function () {
    var $this = $(this);
    $this.attr('data-original-title','copied');
    var url = $(this).attr('data-url');
    copyToClipboard(url);
    $this.tooltip('show');

    setTimeout(function() { 
        var t = 'copy player link';
        $this.attr('data-original-title',t);
    }, 1500);

});

$(document).on('click', '.copy-embed-code', function () {
    var $this = $(this);
    $this.attr('data-original-title','copied');
    var url = $(this).attr('data-url');
    var embed = '<iframe src="'+url+'" frameborder="0" allowFullScreen="true" width="640" height="320"></iframe>';
    copyToClipboard(embed);
    $this.tooltip('show');
    setTimeout(function() { 
        var t = 'copy embed code';
        $this.attr('data-original-title',t);
    }, 1500);
    
});



function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}


$(".del-link").on('click', function(){
    
    var id = $(this).attr('data-id');
    $("#del-link").attr('data-id',id);

    if(!$("#del-link").hasClass('s-del-link'))
    {
        $("#del-link").addClass('s-del-link');
    }
    
    $("#del-confirm .ctxt").text('link');
    $("#del-confirm").modal('show');

});


$(document).on('click', '.s-del-link', function () {
  
    var id = $(this).attr('data-id');
    var $this = $(this);
    $this.attr('disabled','disabled');
    $.ajax({
        type: "GET",
        url: PROOT + '/ajax',
        data: 'id=' + id + '&type=delete-link',
        cache: false,
        success: function (data) {
            console.log(data);
            if(data.success)
            {
                $('#link-'+id).remove();
            }
            $("#del-confirm").modal('hide');
            $this.removeAttr('disabled');
            
            $('#delete-confirmation').modal('hide');
        },
        error: function (xhr) { // if error occured
            alert("Error occured.please try again");
            $this.removeAttr('disabled');
            $("#del-confirm").modal('hide');
        }
    });


});



  $('.datatable').DataTable({
    "order": [],
    "columnDefs": [ {
      "targets": 'no-sort',
      "orderable": false,
} ]
} );





    $("#import-link").on('click', function(){

        var linkList = $("#link-list").val().split('\n');
    
        var links = [];
    
    
        for (var i=0; i < linkList.length; i++) {
            if (/\S/.test(linkList[i])) {
                links.push($.trim(linkList[i]));
            }
        }
    
    
        if(links.length > 0)
        {
            var totalLinks = links.length;
            $('.t-links, .p-links').text(totalLinks);
            importing();
            addLinks(links);
    
            $('.df').removeClass('d-none');
    
        }
        
    });
    

function importing()
{
    var html = '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="sr-only">Loading...</span></div>&nbsp;Importing';
    $("#import-link").html(html);
    $("#import-link, #link-list").attr('disabled','disabled');
   
}



function addLinks(links)


{


    if(typeof links[0] !== 'undefined') {
        
        var v_url = links[0];
        links.splice(0,  1);
       
        $.ajax({
            type: "GET",
            url: PROOT + '/ajax',
            data: 'url=' + v_url + '&type=import-link',
            cache: false,
            success: function (data) {
                console.log(data);
                if(data.success)
                {
                    if(data.title.length == 0)
                    {
                        data.title = v_url ;
                    }
                    bi_add_response(data.title, 'success' , data.plyr);
                }
                else
                {
                    bi_add_response(v_url , 'danger',data.error);
                }
                updateImportStatus(data.success);
                addLinks(links);
                
            },
            error: function (xhr) { // if error occured
                console.log("Error occured.please try again. -> " + v_url );
                updateImportStatus(false);
                addLinks(links);
              
            }
        });


    }
    else {
        // does exist
        imported();
    }




}



$(document).on('click', '.edit-vast', function () {
  

    var vid = $(this).attr("data-id");
    var vtitle = $(this).attr("data-title");
    var voffset = $(this).attr("data-offset");
    var vskipoffset = $(this).attr("data-skipoffset");
    var vtype = $(this).attr("data-type");
    var vfile = $(this).attr("data-file");

    $("#vast-id").val(vid);
    $("#vast-title").val(vtitle);
    $("#vast-offset").val(voffset);
    $("#vast-file").val(vfile);
    $('#vast-type option[value="'+vtype+'"]').attr("selected", "selected");

    if(vtype == 'video')
    {
        $("#vast-offset").val(vskipoffset);
        $(".skipoff-input").removeClass('d-none');
    }

   



});


$('#vast-type').on('change', function () {
    //ways to retrieve selected option and text outside handler
    if(this.value == 'video')
    {
        $(".skipoff-input").removeClass('d-none');
    }
    else
    {
        if(!$('.skipoff-input').hasClass('d-none'))
        {
            $(".skipoff-input").addClass('d-none');
        }
    }
  });
















function imported()
{
    var html = 'Import';
    $("#import-link").html(html);
    $("#import-link, #link-list").removeAttr('disabled');
}

function bi_add_response(msg, type='',  error ='')
{

    if(type == 'danger')
    { 
        mtype = 'failed';
    }
    else
    {
        mtype = 'success';
    }

    var html = '<li class="list-group-item" style="    display: list-item;"> '+msg+ '<b class="float-right text-'+type+'" >'+mtype+'</b> ';
    if(type == 'danger')
    {
        html += '<br> <small class="text-danger">'+error+'</small>';
    }
    else
    {
        html += '<br><small> <span class="badge bg-blue">Player URL :  </span>&nbsp; <span class="plyr-link">'+error+'</span>  &nbsp;<a href="javascript:void(0)" class="text-info copy-plyr-link2" > <b>copy</b> </a></small>    ';
    }
    html += '  </li>';
    
    $("#mi-response").append(html);
}


function updateImportStatus(success = false)
{
    var p_link = $('.p-links').text();
    var s_link = $('.s-links').text();
    var f_link = $('.f-links').text();

    if(p_link != 0)
    {
        $('.p-links').text(parseInt(p_link)-1);
    }
if(success)
{
    $('.s-links').text(parseInt(s_link)+1);
}
else
{
    $('.f-links').text(parseInt(f_link)+1);
}
   


}

$(document).on('click', '#clear-logs' ,function() {
    $("#mi-response").html('');

});





$(document).on('change', '.delete-item' ,function() {
    if($(this).prop('checked')) {
            $(this).parent().parent().addClass('selected-for-delete');
    } else {
            $(this).parent().parent().removeClass('selected-for-delete');
    }
    upDel();

});


function upDel(){
    var selected = 0;
    selected = $(".selected-for-delete").length;
    if(selected != 0){
            $(".delete-selecetd-items").removeClass('d-none');
    }else{
            $(".delete-selecetd-items").addClass('d-none');
    }
    $(".delete-selecetd-items b").text(selected);
}

$(document).on('click', '.delete-selecetd-items' ,function() {

    $('#del-confirm .ctxt').text('selected links');
    $("#del-confirm .dlo").attr('id','delete-selecetd-items');
    $("#del-confirm").modal('show');


});

$(document).on('click', '#delete-selecetd-items' ,function() {
        var ids = '';
        $('.selected-for-delete').each(function(i, obj) {
                ids += $(this).attr('data-id') + ',';
        });

        var $this= $(this);
        $this.text('Please wait...');
        $this.attr('disabled','disabled');

        
        var data = 'ids=' + ids + '&type=delete-link-list';

        $.ajax({
            type: "GET",
            url: PROOT + '/ajax',
            data: data,
            cache: false,
            success: function (data) {
                if (data.success) {
                    //success
                } else {
                    alert('Can not delete this links !');
                }
                location.reload();
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
                location.reload();
            }


        });




});


$("#select_all").change(function() {

    // var movieId = $(this).attr('data-movie-id');

    $('.delete-item').parent().parent().removeClass('selected-for-delete');
    if($(this).prop('checked')) {
            $('.delete-item').prop('checked', true);
            $('.delete-item').parent().parent().addClass('selected-for-delete');

    } else {
            $('.delete-item').prop('checked', false);

    }
    upDel();
});









$("#clear-cache").on('click', function(){


   var html = '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="sr-only">Loading...</span></div>&nbsp;please wait...';
    $(this).html(html);
    var $this = $(this);
    $this.attr('disabled','disabled');


        $.ajax({
            type: "GET",
            url: PROOT + '/ajax',
            data: 'type=clear-cache',
            cache: false,
            success: function (data) {
                
                $("#cache-size").text('0 B');
                $this.html('clear cache');
                
                
            },
            error: function (xhr) { // if error occured
                console.log("Error occured.please try again. -> " + v_ip );
                
              
            }
        });



});


$("#removeLogo").on('click', function(){
    $("#logoVal").val('');
    $("#logoImg").remove();
    $(this).remove();
});

$("#removeFav").on('click', function(){
    $("#favVal").val('');
    $("#favIco").remove();
    $(this).remove();
});



$("#check-proxy").on('click', function(){

    var proxyList = $("#proxy-list").val().split(',');

    var proxy = [];



    for (var i=0; i < proxyList.length; i++) {
        if (/\S/.test(proxyList[i])) {
            proxy.push($.trim(proxyList[i]));
        }
    }


    if(proxy.length > 0)
    {
        var totalProxy = proxy.length;
        $('.t-proxy').text(totalProxy);
        // $('.t-links, .p-links').text(totalLinks);
        checking();
        checkProxy(proxy);

        $('.proxy-progress').removeClass('d-none');

       console.log(proxy);

    }
    
});






function checkProxy(proxy)
{

    if(typeof proxy[0] !== 'undefined') {

        var v_ip = proxy[0];
        proxy.splice(0,  1);

        $.ajax({
            type: "GET",
            url: PROOT + '/ajax',
            data: 'ip=' + v_ip + '&type=check-proxy',
            cache: false,
            success: function (data) {
                updateCheckedStatus();
                checkProxy(proxy);
                
                
            },
            error: function (xhr) { // if error occured
                console.log("Error occured.please try again. -> " + v_ip );
                updateCheckedStatus();
                checkProxy(proxy);
              
            }
        });


    }
    else {
        // does exist
        checked();
        window.location.reload();


    }




}




function updateCheckedStatus()
{
    var p_proxy = $('.p-proxy').text();
    var t_proxy = $('.t-proxy').text();

    if(p_proxy != t_proxy)
    {
        $('.p-proxy').text(parseInt(p_proxy)+1);

      
        var st = Math.round(((parseInt(p_proxy) + 1) * 100) / parseInt(t_proxy));
        $(".p-valume").text(st + '% completed');
       


        $(".progress .progress-bar").attr('style', 'width:' + st + '%');
        $(".progress .progress-bar").attr('aria-valuenow', st);

        

        



    }
   



}






function checking()
{
    var html = '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="sr-only">Loading...</span></div>&nbsp;checking...';
    $("#check-proxy").html(html);
    $("#check-proxy").attr('disabled','disabled');
}




function checked()
{
    var html = 'Check proxies';
    $("#check-proxy").html(html);
    $("#check-proxy").removeAttr('disabled');
}


















$("#copyStreamLink").on('click', function(){
    var txt = $("#streamLink").val();
    var $this = $(this);
    copyToClipboard(txt);
    $this.text('copied');
    setTimeout(
        function()
        { 
            $this.text('copy');
         }, 2000
    );
});

$("#copyEmbedCode").on('click', function(){
    var txt = $("#embedCode").val();
    var $this = $(this);
    copyToClipboard(txt);
    $this.text('copied');
    setTimeout(
        function()
        { 
            $this.text('copy');
         }, 3000
    );
});

$("#copyPlyrLink").on('click', function(){
    var txt = $("#plyrLink").val();
    var $this = $(this);
    copyToClipboard(txt);
    $this.text('copied');
    setTimeout(
        function()
        { 
            $this.text('copy');
         }, 3000
    );
});