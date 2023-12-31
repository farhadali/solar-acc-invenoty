$(function () {

	var loadFile = function(event,_id) {
    var ids = `output_${_id}`;
    var output = document.getElementById('output_'+_id);
    output.src = URL.createObjectURL(event.target.files[0]);
    console.log(event.target.files[0])
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };

$("._account_head_id").on('change',function(){
  var _account_head_id = $(this).val();
  var _nv_url = $(this).attr("type_base_group");
  var request = $.ajax({
      url: _nv_url,
      method: "GET",
      data: { id : _account_head_id },
      dataType: "html"
    });
     
    request.done(function( msg ) {
      $( "._account_groups" ).html( msg );
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

})


function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}


// Example usage:

// $(document).on('keyup','._search_ledger_id',delay(function(e){
//     $(document).find('._search_ledger_id').removeClass('required_border');
//     var ledger_search_url = $("#ledger_search_url").val();
//   var _gloabal_this = $(this);
//   var _text_val = $(this).val().trim();
// console.log($(this).val());

//   var request = $.ajax({
//       url: ledger_search_url,
//       method: "GET",
//       data: { _text_val : _text_val },
//       dataType: "JSON"
//     });
     
//     request.done(function( result ) {

//       var search_html =``;
      
//       var data = result.data; 
//       console.log(data)
//       if(data.length > 0 ){
        
//             search_html +=`<div class="card"><table style="width: 280px;">
//                             <tbody>`;
//                         for (var i = 0; i < data.length; i++) {
//                          search_html += `<tr class="search_row" >
//                                         <td>${data[i].id}
//                                         <input type="hidden" name="_id_ledger" class="_id_ledger" value="${data[i].id}">
//                                         </td><td>${data[i]._name}
//                                         <input type="hidden" name="_name_leder" class="_name_leder" value="${data[i]._name}">
//                                         </td></tr>`;
//                         }                         
//             search_html += ` </tbody> </table></div>`;
//       }else{
//         search_html +=`<div class="card"><table style="width: 300px;"> 
//         <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
//       }     
//       _gloabal_this.parent('td').find('.search_box').html(search_html);
//       _gloabal_this.parent('td').find('.search_box').addClass('search_box_show').show();
      
//     });
     
//     request.fail(function( jqXHR, textStatus ) {
//       alert( "Request failed: " + textStatus );
//     });

  

// }, 500));


$(document).on('click','.search_row',function(){
  var _id = $(this).children('td').find('._id_ledger').val();
  var _name = $(this).find('._name_leder').val();
  $(this).parent().parent().parent().parent().parent().parent().find('._ledger_id').val(_id);
  var _id_name = `${_name} `;
  $(this).parent().parent().parent().parent().parent().parent().find('._search_ledger_id').val(_id_name);


  $('.search_box').hide();
  $('.search_box').removeClass('search_box_show').hide();
})

$(document).on('click',function(){
    var searach_show= $('.search_box').hasClass('search_box_show');
    if(searach_show ==true){
      $('.search_box').removeClass('search_box_show').hide();
    }
})



 $(document).ajaxSend(function(){
      $(".ajax_loader").fadeIn(250);
  });
  $(document).ajaxComplete(function(){
      $(".ajax_loader").fadeOut(250);
  });



})



 function check_select_org_branch_cost_center(){
    var _master_organization_id = $(document).find("._master_organization_id").val();
    var _master_branch_id = $(document).find("._master_branch_id").val();
    var _cost_center_id = $(document).find("._cost_center_id").val();
    if(_master_organization_id ==""){
      alert('Please Select Organization/Company');
        return false;
      }
      if(_master_branch_id ==""){
        alert('Please Select Branch/Division');
        return false;
      }

      if(_cost_center_id ==""){
        alert('Please Select Cost Center/Project');
        return false;
      }
  }
  
  $(document).on('change','._master_branch_id',function(){
     //var _master_branch_id = $(this).val();
     //change_all_branch(_master_branch_id);

    change_branch_cost_strore();
  })


  function change_branch_cost_strore(){
    var _master_branch_id = $(document).find("._master_branch_id").val();
    var _cost_center_id = $(document).find("._cost_center_id").val();
    var _master_store_id = $(document).find("._master_store_id").val();

    $(document).find("._main_branch_id_detail").val(_master_branch_id).change();
    $(document).find("._main_cost_center").val(_cost_center_id).change();
   // $(document).find("._main_store_id").val(_master_store_id).change();


    $(document).find("._branch_id_detail").val(_master_branch_id).change();
    $(document).find("._cost_center").val(_cost_center_id).change();




  }

 


  $(document).on('change','._cost_center_id',function(){
     //var _cost_center_id = $(this).val();
     //change_all_cost_center(_cost_center_id);
     change_branch_cost_strore();
  })
  $(document).on('change','._master_store_id',function(){
    // var _master_store_id = $(this).val();
     //change_all_store(_master_store_id);
     change_branch_cost_strore();
  })


 function change_all_branch(_master_branch_id){
    $(document).find("._main_branch_id_detail").val(_master_branch_id).change();
  }

  function change_all_cost_center(_cost_center_id){
    $(document).find("._main_cost_center").val(_cost_center_id).change();
  }


  function change_all_store(_master_store_id){
    $(document).find("._main_store_id").val(_master_store_id).change();
  }



$(document).ready(function(){
  $(document).find("#spinner_div").hide();
})

  $(document).on('click','.attr_base_create_url',function(){
    $(document).find("#spinner_div").show();
    var create_url = $(this).attr('attr_base_create_url');
    var request = $.ajax({
      url: create_url,
      method: "GET",
      dataType: "html",
      async:true,
    });
     
    request.done(function( msg ) {
      $(document).find("#spinner_div").hide();
      $( "#commonEntryModalForm" ).html( msg );

    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
  })

  $(document).on('click','.attr_base_edit_url',function(){
    $(document).find("#spinner_div").show();
    var edit_url = $(this).attr('attr_base_edit_url');
    var request = $.ajax({
      url: edit_url,
      method: "GET",
      dataType: "html",
      async:true,
    });
     
    request.done(function( msg ) {
       $(document).find("#spinner_div").hide();
      $( "#commonEntryModalForm" ).html( msg );

    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
  })


  var loadFile = function(event,_id) {
    var ids = `output_${_id}`;
    var output = document.getElementById('output_'+_id);
    output.src = URL.createObjectURL(event.target.files[0]);
    console.log(event.target.files[0])
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };

$("form  .card-header").css({"background-color": "#f5f5f5"});
//$("form  .card-body").css({"margin-left":"-10px","margin-right":"-10px"});

$(document).on('keyup','#opening_dr_amount',function(){
  $(document).find("#opening_cr_amount").val(0);

})

$(document).on('keyup','#opening_cr_amount',function(){
  $(document).find("#opening_dr_amount").val(0);

})

  function _show_notify_message(_message,_type){
    $(document).find("#_notify_message_box").removeClass();
    $(document).find("#_notify_message_box").addClass(_type);
    $(document).find("#_notify_message_box").text(_message);

    $(document).find("#_notify_message_box").show().delay(5000).fadeOut();
  }

  $(function () {

    var default_date_formate = $(document).find("#default_date_formate").val();
    // Summernote

    
    
    $(document).find('.select2').select2()
     $('#reservationdate').datetimepicker({
        format:default_date_formate

    });
     

  })

  var project_base_url = $("#project_base_url").val();
  console.log("project_base_url "+project_base_url)

  $(document).on("change","._account_head_id",function(){
      var _account_head_id = $(this).val();
      var action_url = project_base_url+"/"+'type_base_group';
      var request = $.ajax({
          url: action_url,
          method: "GET",
          data: { id : _account_head_id },
          dataType: "html"
        });
         
        request.done(function( msg ) {
          $(document).find("._account_groups" ).html( msg );
        });
         
        request.fail(function( jqXHR, textStatus ) {
          alert( "Request failed: " + textStatus );
        });
  })




function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}


$(document).on('keyup','._search_main_delivery_man',delay(function(e){
    $(document).find('._search_main_delivery_man').removeClass('required_border');
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var action_url = project_base_url+"/"+'ledger-search';
  var request = $.ajax({
      url: action_url,
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 300px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_delivery_man" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_delivery_man_ledger" class="_delivery_man_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="delivery_man_name_leder" class="delivery_man_name_leder" value="${data[i]._name}">
                                        <input type="hidden" name="delivery_man_address" class="delivery_man_address" value="${data[i]._address}">
                                        <input type="hidden" name="delivery_man_phone" class="delivery_man_phone" value="${data[i]._phone}">
                                        </td>
                                       
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3"><button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button></th></thead><tbody></tbody></table></div>`;
      }     
      $(document).find('.search_box_delivery_man').html(search_html);
      $(document).find('.search_box_delivery_man').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));


$(document).on('keyup','._search_main_delivery_man',delay(function(e){
    $(document).find('._search_main_delivery_man').removeClass('required_border');
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var action_url = project_base_url+"/"+'ledger-search';
  var request = $.ajax({
      url: action_url,
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 300px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_delivery_man" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_delivery_man_ledger" class="_delivery_man_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="delivery_man_name_leder" class="delivery_man_name_leder" value="${data[i]._name}">
                                        <input type="hidden" name="delivery_man_address" class="delivery_man_address" value="${data[i]._address}">
                                        <input type="hidden" name="delivery_man_phone" class="delivery_man_phone" value="${data[i]._phone}">
                                        </td>
                                       
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3"><button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button></th></thead><tbody></tbody></table></div>`;
      }     
      $(document).find('.search_box_delivery_man').html(search_html);
      $(document).find('.search_box_delivery_man').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

$(document).on('keyup','._manufacture_company',delay(function(e){
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var action_url = project_base_url+"/"+'manufacture-comapany-search';
  var request = $.ajax({
      url: action_url,
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 300px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_manu_comapany" >
                                        <td>${data[i]._manufacture_company}
                                        </td></tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }     
      $(document).find('.search_boxManufacCompany').html(search_html);
      $(document).find('.search_boxManufacCompany').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
}, 500));

$(document).on('click','.search_manu_comapany',function(){

  var company_name = $(this).text().trim();
  console.log(company_name)
$(document).find("._manufacture_company").val(company_name);

  $(document).find('.search_boxManufacCompany').hide();
  $(document).find('.search_boxManufacCompany').removeClass('search_box_show').hide();
})


$(document).on('click','.search_row_delivery_man',function(){
  var _id =$(this).find('._delivery_man_ledger').val();
  var _name = $(this).find('.delivery_man_name_leder').val();
  var _id_name = `${_id} ${_name}`
  $(document).find('._delivery_man').val(_id);
  $(document).find('._search_main_delivery_man').val(_id_name);


  $(document).find('.search_box_delivery_man').hide();
  $(document).find('.search_box_delivery_man').removeClass('search_box_show').hide();
})


$(document).on('keyup','._search_main_sales_man',delay(function(e){
    $(document).find('._search_main_sales_man').removeClass('required_border');
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var action_url = project_base_url+"/"+'ledger-search';
  var request = $.ajax({
      url: action_url,
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 300px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_sales_man" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_sales_man_ledger" class="_sales_man_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="sales_man_name_leder" class="sales_man_name_leder" value="${data[i]._name}">
                                        <input type="hidden" name="sales_man_address" class="sales_man_address" value="${data[i]._address}">
                                        <input type="hidden" name="sales_man_phone" class="sales_man_phone" value="${data[i]._phone}">
                                        </td>
                                       
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }     
      $(document).find('.search_box_sales_man').html(search_html);
      $(document).find('.search_box_sales_man').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
}, 500));


$(document).on('click','.search_row_sales_man',function(){
  var _id = $(this).find('._sales_man_ledger').val();
  var _name = $(this).find('.sales_man_name_leder').val();
  var _id_name = `${_id} ${_name}`
  $(document).find('._sales_man').val(_id);
  $(document).find('._search_main_sales_man').val(_id_name);


  $(document).find('.search_box_sales_man').hide();
  $(document).find('.search_box_sales_man').removeClass('search_box_show').hide();
})




//user_id_name

//Employe Search 

$(document).on('keyup','.user_id_name',delay(function(e){
    
    var action_url = project_base_url+"/"+'employee-search';
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var request = $.ajax({
      url: action_url,
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 300px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="_employee_search_row _cursor_pointer" >
                                        <td>${data[i]._code}
                                        <input type="hidden" name="_emplyee_row_id" class="_emplyee_row_id" value="${data[i].id}">
                                        <input type="hidden" name="_emplyee_row_code_id" class="_emplyee_row_code_id" value="${data[i]._code}">
                                        </td>
                                        <td>${data[i]._name}
                                        <input type="hidden" name="_search_employee_name" class="_search_employee_name" value="${data[i]._name}">
                                        
                                        </td>
                                        
                                       
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }   

       _gloabal_this.parent('td').find('.search_box_employee').html(search_html);
      _gloabal_this.parent('td').find('.search_box_employee').addClass('search_box_show').show();  
      
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
}, 500));


$(document).on('click','._employee_search_row',function(){
 var employee_row_id = $(this).children('td').find('._emplyee_row_id').val();
 var employee_code_id = $(this).children('td').find('._emplyee_row_code_id').val();
 var employee_name = $(this).children('td').find('._search_employee_name').val();
 console.log(employee_name)
 var _code_and_name = `${employee_code_id},${employee_name}`;

$(this).parent().parent().parent().parent().parent().parent().find('.user_id_name').val(_code_and_name);
$(this).parent().parent().parent().parent().parent().parent().find('.user_row_id').val(employee_row_id);
$(this).parent().parent().parent().parent().parent().parent().find('.user_id').val(employee_code_id);


  $(document).find('.search_box_employee').hide();
  $(document).find('.search_box_employee').removeClass('search_box_show').hide();
})


// Example usage:

$(document).on('keyup','._search_ledger_id',delay(function(e){
    $(document).find('._search_ledger_id').removeClass('required_border');
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var _head_no = $(this).attr('attr_account_head_no');
  if(isNaN(_head_no)){ _head_no=0 }
    console.log("_text_val "+_text_val)
    console.log("_head_no "+_head_no)
    var action_url = project_base_url+"/"+'ledger-search';
    console.log(action_url)
  var request = $.ajax({
      url: action_url,
      method: "GET",
      data: { _text_val,_head_no },
      dataType: 'json'
    });
     
    request.done(function( result ) {
      console.log(result)
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 400px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_ledger" class="_id_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_leder" class="_name_leder" value="${data[i]._name}">
                                        <input type="hidden" name="_s_l_address" class="_s_l_address" value="${data[i]._address}">
                                        <input type="hidden" name="_s_l_phone" class="_s_l_phone" value="${data[i]?._phone}">
                                        <input type="hidden" name="_s_l_balance" class="_s_l_balance" value="${data[i]?._balance}">
                                        </td>
                                        <td>${data[i]?._phone}</td>
                                        <td>${data[i]?._balance}</td>
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3"><button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button></th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('td').find('.search_box').html(search_html);
      _gloabal_this.parent('td').find('.search_box').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      console.log(jqXHR)
      console.log(textStatus)
      alert( "Request failed: " + textStatus );
    });

  

}, 500));


$(document).on('click','.search_row',function(){
  var _id = $(this).children('td').find('._id_ledger').val();
  var _name = $(this).find('._name_leder').val();
  var _s_l_balance = $(this).find('._s_l_balance').val();
  console.log(_s_l_balance)
  $(this).parent().parent().parent().parent().parent().parent().find('._ledger_id').val(_id);
  var _id_name = `${_name},`+_s_l_balance;
  $(this).parent().parent().parent().parent().parent().parent().find('._search_ledger_id').val(_id_name);


  $(document).find('.search_box').hide();
  $(document).find('.search_box').removeClass('search_box_show').hide();
})


$(document).on('keyup','._search_main_ledger_id',delay(function(e){
    $(document).find('._search_main_ledger_id').removeClass('required_border');
    var _gloabal_this = $(this);
    var _text_val = $(this).val().trim();
    var _form = $(document).find("._search_form_value").val();
     var action_url = project_base_url+"/"+'main-ledger-search';
     
    console.log(action_url)

  var request = $.ajax({
      url: action_url,
      method: "GET",
      data: { _text_val,_form },
      dataType: "JSON"
    });
     
    request.done(function( result ) {

      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table class="_ledger_filter_table">
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_ledger" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_main_ledger" class="_id_main_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_main_ledger" class="_name_main_ledger" value="${data[i]._name}">
                                        <input type="hidden" name="_address_main_ledger" class="_address_main_ledger" value="${data[i]._address}">
                                        <input type="hidden" name="_phone_main_ledger" class="_phone_main_ledger" value="${data[i]._phone}">
                                  
                                   </td>
                                   <td>${data[i]?._phone}</td>
                                   <td>${data[i]?._balance}</td>
                                   </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3"><button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger">
                   <i class="nav-icon fas fa-plus"></i> New Ledger 
                </button></th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('div').find('.search_box_main_ledger').html(search_html);
      _gloabal_this.parent('div').find('.search_box_main_ledger').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));


  $(document).on("click",'.search_row_ledger',function(){
    var _id = $(this).children('td').find('._id_main_ledger').val();
    var _name = $(this).find('._name_main_ledger').val();
    var _address_main_ledger = $(this).find('._address_main_ledger').val();
    var _phone_main_ledger = $(this).find('._phone_main_ledger').val();
    $(document).find("._main_ledger_id").val(_id);
    $(document).find("._search_main_ledger_id").val(_name);
    $(document).find("._phone").val(_phone_main_ledger);
    $(document).find("._address").val(_address_main_ledger);

    $(document).find('.search_box_main_ledger').hide();
    $(document).find('.search_box_main_ledger').removeClass('search_box_show').hide();
  })


  $(document).on('click','._voucher_row_remove',function(event){
      event.preventDefault();
      var ledger_id = $(this).parent().parent('tr').find('._ledger_id').val();
      if(ledger_id ==""){
          $(this).parent().parent('tr').remove();
      }else{
        if(confirm('Are you sure your want to delete?')){
          $(this).parent().parent('tr').remove();
        } 
      }
      _voucher_total_calculation();
  })

  function _voucher_total_calculation(){
    var _total_dr_amount = 0;
    var _total_cr_amount = 0;
      $(document).find("._cr_amount").each(function() {
          _total_cr_amount +=parseFloat($(this).val());
      });
      $(document).find("._dr_amount").each(function() {
          _total_dr_amount +=parseFloat($(this).val());
      });
      $(document).find("._total_dr_amount").val(_math_round(_total_dr_amount));
      $(document).find("._total_cr_amount").val(_math_round(_total_cr_amount));
  }


  $(document).on('keyup','._dr_amount',function(){
    $(this).parent().parent('tr').find('._cr_amount').val(0);
    $(document).find("._total_dr_amount").removeClass('required_border');
    $(document).find("._total_cr_amount").removeClass('required_border');
    _voucher_total_calculation();
  })



  $(document).on('keyup','._cr_amount',function(){
     $(this).parent().parent('tr').find('._dr_amount').val(0);
     $(document).find("._total_dr_amount").removeClass('required_border');
      $(document).find("._total_cr_amount").removeClass('required_border');
    _voucher_total_calculation();
  })


  function _math_round(_amount,_param=1){
    return Math.round(_amount);
      
  }


  
  $(document).on('change','._voucher_type',function(){
    $(document).find('._voucher_type').removeClass('required_border');
  })

  $(document).on('keyup','._note',function(){
    $(document).find('._note').removeClass('required_border');
  })

  $(document).on('click','._save_and_print',function(){
    $(document).find('._save_and_print_value').val(1);
  })



function _common_click_function(){

    var searach_show= $(document).find('.search_box_item').hasClass('search_box_show');
    var search_box_main_ledger= $(document).find('.search_box_main_ledger').hasClass('search_box_show');
    var search_box_delivery_man= $(document).find('.search_box_delivery_man').hasClass('search_box_show');
    var search_box_sales_man= $(document).find('.search_box_sales_man').hasClass('search_box_show');
    var search_box_purchase_order = $(document).find('.search_box_purchase_order').hasClass('search_box_show');
    var search_box= $(document).find('.search_box').hasClass('search_box_show');
    var _dr_search_box= $(document).find('._dr_search_box').hasClass('search_box_show');
    var _cr_search_box= $(document).find('._cr_search_box').hasClass('search_box_show');
    var search_boxManufacCompany= $(document).find('.search_boxManufacCompany').hasClass('search_box_show');
    var search_box_ledger= $(document).find('.search_box_ledger').hasClass('search_box_show');
    var search_box_employee= $(document).find('.search_box_employee').hasClass('search_box_show');
    var search_box_supplier= $(document).find('.search_box_supplier').hasClass('search_box_show');
    var search_box_master_purchase= $(document).find('.search_box_master_purchase').hasClass('search_box_show');



    if(search_box_master_purchase ==true){
      $(document).find('.search_box_master_purchase').removeClass('search_box_show').hide();
    }
    if(search_box_supplier ==true){
      $(document).find('.search_box_supplier').removeClass('search_box_show').hide();
    }
    if(search_box_employee ==true){
      $(document).find('.search_box_employee').removeClass('search_box_show').hide();
    }
    if(searach_show ==true){
      $(document).find('.search_box_item').removeClass('search_box_show').hide();
    }
    if(search_box_ledger ==true){
      $(document).find('.search_box_ledger').removeClass('search_box_show').hide();
    }

    if(_dr_search_box ==true){
      $(document).find('._dr_search_box').removeClass('search_box_show').hide();
    }

    if(_cr_search_box ==true){
      $(document).find('._cr_search_box').removeClass('search_box_show').hide();
    }

    if(search_box ==true){
      $(document).find('.search_box').removeClass('search_box_show').hide();
    }

    
    if(search_box_purchase_order ==true){
      $(document).find('.search_box_purchase_order').removeClass('search_box_show').hide();
    }
    if(search_box_main_ledger ==true){
      $(document).find('.search_box_main_ledger').removeClass('search_box_show').hide();
    }
    if(search_box_delivery_man ==true){
      $(document).find('.search_box_delivery_man').removeClass('search_box_show').hide();
    }

    if(search_box_sales_man ==true){
      $(document).find('.search_box_sales_man').removeClass('search_box_show').hide();
    }
    
    if(search_boxManufacCompany ==true){
      $(document).find('.search_boxManufacCompany').removeClass('search_box_show').hide();
    }

     var _u_barcode= $(document).find('.amsify-input-group-addon').hasClass('show-plus-bg');

     if(_u_barcode ==true){
      var _form_name = $(document).find("._form_name").val();
      if(_form_name =='sales_return'){
            $(document).find('.show-plus-bg').each(function(index){


               var _qty = parseFloat($(this).text());
              if(isNaN(_qty)){ _qty=0 }
               $(this).closest('tr').find('._qty').val(_qty);
                //Check valid Barcode
               var _old_barcodes = $(this).closest('tr').find('._old_barcode').val();
               var _old_barcodes_array = _old_barcodes.split(",");
               var new_barcode_array=[];
               $(this).closest('tr').find('.amsify-select-tag').each(function(){
                      let single_barcode = $(this).attr("data-val");
                      let check_barcode =  _old_barcodes_array.includes(single_barcode);
                      if(check_barcode===false){
                        alert(" This Barcode Is not Valid !!!");
                        $(this).remove();
                      }else{
                        if (!new_barcode_array.includes(single_barcode)){
                            new_barcode_array.push(single_barcode);
                        }
                            
                        
                      }
                    
               });
              var _string_barcode = new_barcode_array.toString();
               var _qty = new_barcode_array.length
               $(this).text(_qty);
               $(this).closest('tr').find('._qty').val(_qty);
               $(this).closest('tr').find('._barcode').val(_string_barcode);
               //End of Check Valid Barcode


               var _sales_rate = $(this).closest('tr').find('._sales_rate').val();
               var _pur_rate   = $(this).closest('tr').find('._rate').val();
               var _sales_vat = $(this).closest('tr').find('._vat').val();
               var _sales_discount = $(this).closest('tr').find('._discount').val();

               




               if(isNaN(_sales_rate)){ _sales_rate=0 }
               if(isNaN(_pur_rate)){ _pur_rate=0 }
               if(isNaN(_sales_vat)){ _sales_vat=0 }
               _vat_amount = ((_sales_rate*_sales_vat)/100)
               if(isNaN(_sales_discount)){ _sales_discount=0 }
               _discount_amount = ((_sales_rate*_sales_discount)/100);
               var _value = (parseFloat(_qty)*parseFloat(_sales_rate));

              $(this).closest('tr').find("._discount").val(_sales_discount);
              $(this).closest('tr').find("._qty").val(_qty);
              $(this).closest('tr').find("._sales_rate").val(_sales_rate);
              $(this).closest('tr').find("._discount_amount").val(_discount_amount);
              $(this).closest('tr').find("._vat").val(_sales_vat);
              $(this).closest('tr').find("._vat_amount").val(_vat_amount);
              $(this).closest('tr').find("._value").val(_value);
          })
             _purchase_total_calculation();

      }

       if(_form_name =='purchases'){
            $(document).find('.show-plus-bg').each(function(index){
               var _qty = parseFloat($(this).text());
              if(isNaN(_qty)){ _qty=0 }
               $(this).closest('tr').find('._qty').val(_qty);
               var _sales_rate = $(this).closest('tr').find('._sales_rate').val();
               var _pur_rate   = $(this).closest('tr').find('._rate').val();
               var _sales_vat = $(this).closest('tr').find('._vat').val();
               var _sales_discount = $(this).closest('tr').find('._discount').val();

               if(isNaN(_sales_rate)){ _sales_rate=0 }
               if(isNaN(_pur_rate)){ _pur_rate=0 }
               if(isNaN(_sales_vat)){ _sales_vat=0 }
               _vat_amount = ((_pur_rate*_sales_vat)/100)
               if(isNaN(_sales_discount)){ _sales_discount=0 }
               _discount_amount = ((_pur_rate*_sales_discount)/100);
               var _value = (parseFloat(_qty)*parseFloat(_pur_rate));

              $(this).closest('tr').find("._discount").val(_sales_discount);
              $(this).closest('tr').find("._qty").val(_qty);
              $(this).closest('tr').find("._sales_rate").val(_sales_rate);
              $(this).closest('tr').find("._discount_amount").val(_discount_amount);
              $(this).closest('tr').find("._vat").val(_sales_vat);
              $(this).closest('tr').find("._vat_amount").val(_vat_amount);
              $(this).closest('tr').find("._value").val(_value);
          })
             _purchase_total_calculation();

      }
       if(_form_name =='service_masters'){
            $(document).find('.show-plus-bg').each(function(index){
               var _qty = parseFloat($(this).text());
              if(isNaN(_qty)){ _qty=0 }
               $(this).closest('tr').find('._qty').val(_qty);
               var _sales_rate = $(this).closest('tr').find('._sales_rate').val();
               var _pur_rate   = $(this).closest('tr').find('._rate').val();
               var _sales_vat = $(this).closest('tr').find('._vat').val();
               var _sales_discount = $(this).closest('tr').find('._discount').val();

               if(isNaN(_sales_rate)){ _sales_rate=0 }
               if(isNaN(_pur_rate)){ _pur_rate=0 }
               if(isNaN(_sales_vat)){ _sales_vat=0 }
               _vat_amount = ((_pur_rate*_sales_vat)/100)
               if(isNaN(_sales_discount)){ _sales_discount=0 }
               _discount_amount = ((_pur_rate*_sales_discount)/100);
               var _value = (parseFloat(_qty)*parseFloat(_pur_rate));

              $(this).closest('tr').find("._discount").val(_sales_discount);
              $(this).closest('tr').find("._qty").val(_qty);
              $(this).closest('tr').find("._sales_rate").val(_sales_rate);
              $(this).closest('tr').find("._discount_amount").val(_discount_amount);
              $(this).closest('tr').find("._vat").val(_sales_vat);
              $(this).closest('tr').find("._vat_amount").val(_vat_amount);
              $(this).closest('tr').find("._value").val(_value);
          })
             _purchase_total_calculation();

      }

       if(_form_name =='transfer'){
            $(document).find('.show-plus-bg').each(function(index){
               var _stock_in__qty = parseFloat($(this).text());
              if(isNaN(_stock_in__qty)){ _stock_in__qty=0 }
               $(this).closest('tr').find('._stock_in__qty').val(_stock_in__qty);
               var _stock_in__sales_rate = $(this).closest('tr').find('._stock_in__sales_rate').val();
               var _pur_rate   = $(this).closest('tr').find('._stock_in__rate').val();
               var _sales_vat = $(this).closest('tr').find('._vat').val();
               var _sales_discount = $(this).closest('tr').find('._discount').val();

               if(isNaN(_stock_in__sales_rate)){ _stock_in__sales_rate=0 }
               if(isNaN(_pur_rate)){ _pur_rate=0 }
               if(isNaN(_sales_vat)){ _sales_vat=0 }
               _vat_amount = ((_pur_rate*_sales_vat)/100)
               if(isNaN(_sales_discount)){ _sales_discount=0 }
               _discount_amount = ((_pur_rate*_sales_discount)/100);
               var _value = (parseFloat(_stock_in__qty)*parseFloat(_pur_rate));

              $(this).closest('tr').find("._stock_in__qty").val(_stock_in__qty);
              $(this).closest('tr').find("._stock_in__sales_rate").val(_stock_in__sales_rate);
              $(this).closest('tr').find("._stock_in__value").val(_value);
          })
             _purchase_total_calculation();

      }

       if(_form_name =='production'){
            $(document).find('.show-plus-bg').each(function(index){
               var _stock_in__qty = parseFloat($(this).text());
              if(isNaN(_stock_in__qty)){ _stock_in__qty=0 }
               $(this).closest('tr').find('._stock_in__qty').val(_stock_in__qty);
               var _stock_in__sales_rate = $(this).closest('tr').find('._stock_in__sales_rate').val();
               var _pur_rate   = $(this).closest('tr').find('._stock_in__rate').val();
               var _sales_vat = $(this).closest('tr').find('._vat').val();
               var _sales_discount = $(this).closest('tr').find('._discount').val();

               if(isNaN(_stock_in__sales_rate)){ _stock_in__sales_rate=0 }
               if(isNaN(_pur_rate)){ _pur_rate=0 }
               if(isNaN(_sales_vat)){ _sales_vat=0 }
               _vat_amount = ((_pur_rate*_sales_vat)/100)
               if(isNaN(_sales_discount)){ _sales_discount=0 }
               _discount_amount = ((_pur_rate*_sales_discount)/100);
               var _value = (parseFloat(_stock_in__qty)*parseFloat(_pur_rate));

              $(this).closest('tr').find("._stock_in__qty").val(_stock_in__qty);
              $(this).closest('tr').find("._stock_in__sales_rate").val(_stock_in__sales_rate);
              $(this).closest('tr').find("._stock_in__value").val(_value);
          })
             _purchase_total_calculation();

      }
       

      
       if(_form_name =='purchases_return'){
            $(document).find('.show-plus-bg').each(function(index){

              var _qty = parseFloat($(this).text());
              if(isNaN(_qty)){ _qty=0 }
               $(this).closest('tr').find('._qty').val(_qty);
                //Check valid Barcode
               var _old_barcodes = $(this).closest('tr').find('._old_barcode').val();
               var _old_barcodes_array = _old_barcodes.split(",");
               var new_barcode_array=[];
               $(this).closest('tr').find('.amsify-select-tag').each(function(){
                      let single_barcode = $(this).attr("data-val");
                      let check_barcode =  _old_barcodes_array.includes(single_barcode);
                      if(check_barcode===false){
                        alert(" This Barcode Is not Valid !!!");
                        $(this).remove();
                      }else{
                        if (!new_barcode_array.includes(single_barcode)){
                            new_barcode_array.push(single_barcode);
                        }
                            
                        
                      }
                    
               });
              var _string_barcode = new_barcode_array.toString();
               var _qty = new_barcode_array.length
               $(this).text(_qty);
               $(this).closest('tr').find('._qty').val(_qty);
               $(this).closest('tr').find('._barcode').val(_string_barcode);
               //End of Check Valid Barcode
              
               var _sales_rate = $(this).closest('tr').find('._sales_rate').val();
               var _pur_rate   = $(this).closest('tr').find('._rate').val();
               var _sales_vat = $(this).closest('tr').find('._vat').val();
               var _sales_discount = $(this).closest('tr').find('._discount').val();

               

               if(isNaN(_sales_rate)){ _sales_rate=0 }
               if(isNaN(_pur_rate)){ _pur_rate=0 }
               if(isNaN(_sales_vat)){ _sales_vat=0 }
               _vat_amount = ((_pur_rate*_sales_vat)/100)
               if(isNaN(_sales_discount)){ _sales_discount=0 }
               _discount_amount = ((_pur_rate*_sales_discount)/100);
               var _value = (parseFloat(_qty)*parseFloat(_pur_rate));

              $(this).closest('tr').find("._discount").val(_sales_discount);
              $(this).closest('tr').find("._qty").val(_qty);
              $(this).closest('tr').find("._sales_rate").val(_sales_rate);
              $(this).closest('tr').find("._discount_amount").val(_discount_amount);
              $(this).closest('tr').find("._vat").val(_sales_vat);
              $(this).closest('tr').find("._vat_amount").val(_vat_amount);
              $(this).closest('tr').find("._value").val(_value);

          })
             _purchase_total_calculation();

      }
            if(_form_name =='damage'){
            $(document).find('.show-plus-bg').each(function(index){
               var _qty = parseFloat($(this).text());
              if(isNaN(_qty)){ _qty=0 }
                //Check valid Barcode
               var _old_barcodes = $(this).closest('tr').find('._old_barcode').val();
               var _old_barcodes_array = _old_barcodes.split(",");
               var new_barcode_array=[];
               $(this).closest('tr').find('.amsify-select-tag').each(function(){
                      let single_barcode = $(this).attr("data-val");
                      let check_barcode =  _old_barcodes_array.includes(single_barcode);
                      if(check_barcode===false){
                        alert(" This Barcode Is not Valid !!!");
                        $(this).remove();
                      }else{
                        if (!new_barcode_array.includes(single_barcode)){
                            new_barcode_array.push(single_barcode);
                        }
                            
                        
                      }
                    
               });
              var _string_barcode = new_barcode_array.toString();
               var _qty = new_barcode_array.length
               $(this).text(_qty);
               $(this).closest('tr').find('._qty').val(_qty);
              // $(this).closest('tr').find('._barcode').val(_string_barcode);
               //End of Check Valid Barcode

               var _sales_rate = $(this).closest('tr').find('._sales_rate').val();
               var _pur_rate   = $(this).closest('tr').find('._rate').val();
               var _sales_vat = $(this).closest('tr').find('._vat').val();
               var _sales_discount = $(this).closest('tr').find('._discount').val();

               if(isNaN(_sales_rate)){ _sales_rate=0 }
               if(isNaN(_pur_rate)){ _pur_rate=0 }
               if(isNaN(_sales_vat)){ _sales_vat=0 }
               _vat_amount = ((_sales_rate*_sales_vat)/100)
               if(isNaN(_sales_discount)){ _sales_discount=0 }
               _discount_amount = ((_sales_rate*_sales_discount)/100);
               var _value = (parseFloat(_qty)*parseFloat(_sales_rate));

              $(this).closest('tr').find("._discount").val(_sales_discount);
              $(this).closest('tr').find("._qty").val(_qty);
              $(this).closest('tr').find("._sales_rate").val(_sales_rate);
              $(this).closest('tr').find("._discount_amount").val(_discount_amount);
              $(this).closest('tr').find("._vat").val(_sales_vat);
              $(this).closest('tr').find("._vat_amount").val(_vat_amount);
              $(this).closest('tr').find("._value").val(_value);
          })
             _purchase_total_calculation();

      }


           
     }
}

$(document).on('click',function(){
    _common_click_function();
})

$(document).on('keyup','.amsify-suggestags-input',function(e){
    var code = (e.keyCode ? e.keyCode : e.which);
    if(code == 13) { //Enter keycode
        _common_click_function();
    }
})


$(document).on('click','._pushmenu',function(){

if($(document).find("._pushmenu").hasClass("_left_menu_show")){
  $(document).find('._pushmenu').removeClass('_left_menu_show');
  $(document).find('.main-sidebar').hide();
  $(document).find('._project_main_nav_logo').show();
}else{
  $(document).find('._pushmenu').addClass('_left_menu_show');
  $(document).find('.main-sidebar').show();
  $(document).find('._project_main_nav_logo').hide();

}
  

  
 

})



$(document).on('click','.save_item',function(){
    var _category_id = $(document).find("._category_id").val();
    var _item_item = $(document).find("._item_item").val();
    var _item_code = $(document).find("._item_code").val();
    var _item_unit_id = $(document).find("._item_unit_id").val();
    var _item_barcode = $(document).find("._item_barcode").val();
    var _item_discount = $(document).find("._item_discount").val();
    var _item_vat = $(document).find("._item_vat").val();
    var _item_pur_rate = $(document).find("._item_pur_rate").val();
    var _item_sale_rate = $(document).find("._item_sale_rate").val();
    var _item_manufacture_company = $(document).find("._item_manufacture_company").val();
    var _item_status = $(document).find("._item_status").val();
    var _item_unique_barcode = $(document).find("._item_unique_barcode").val();
    var _kitchen_item = $(document).find("#_kitchen_item").val();
    var _item_opening_qty = $(document).find("#_item_opening_qty").val();
    var _item_branch_id = $(document).find("._item_branch_id").val();
    var _item_cost_center_id = $(document).find("._item_cost_center_id").val();
    var _item_store_id = $(document).find("._item_store_id").val();





    
    var reqired_fields = 0;
    if(_category_id ==""){
       $(document).find('._category_id').addClass('required_border');
       reqired_fields =1;
    }else{
      $(document).find('._category_id').removeClass('required_border');
    }
    if(_item_item ==""){
       $(document).find('._item_item').addClass('required_border');
       reqired_fields =1;
    }else{
      $(document).find('._item_item').removeClass('required_border');
    }
    if(_item_unit_id ==""){
       $(document).find('._item_unit_id').addClass('required_border');
       reqired_fields =1;
    }else{
      $(document).find('._item_unit_id').removeClass('required_border');
    }
    
    if(reqired_fields ==1){
      return false;
    }

    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });

var action_url = project_base_url+"/"+'ajax-item-save';
        $.ajax({
           type:'POST',
           url:action_url,
           data:{_category_id,_item_item,_item_code,_item_unit_id,_item_barcode,_item_discount,_item_vat,_item_pur_rate,_item_sale_rate,_item_manufacture_company,_item_status,_item_unique_barcode,_kitchen_item,_item_store_id,_item_branch_id,_item_cost_center_id,_item_opening_qty
           },
           success:function(data){
            console.log(data)
              if(data !=""){
                if(data==0){
                    alert("This Name has been already Taken");
                }else{
                   alert("Information Save Successfully");
                    $(document).find("._item_modal_form").trigger('reset');
                    $(document).find(".inventoryEntryModal").click();
                }
               
                
              }else{
                alert("Information Not Save");
              }

           }

        });

  })

  $(document).on('click','.save_ledger',function(){
    var _account_head_id = $(document).find("._account_head_id").val();
    var _account_groups = $(document).find("._account_groups").val();
    var _ledger_organization_id = $(document).find("._ledger_organization_id").val();
    var _ledger_cost_center_id = $(document).find("._ledger_cost_center_id").val();
    var _ledger_branch_id = $(document).find("._ledger_branch_id").val();



    var _ledger_name = $(document).find("._ledger_name").val();
    var _ledger_address = $(document).find("._ledger_address").val();
    var _ledger_code = $(document).find("._ledger_code").val();
    var _ledger_short = $(document).find("._ledger_short").val();
    var _ledger_nid = $(document).find("._ledger_nid").val();
    var _ledger_phone = $(document).find("._ledger_phone").val();
    var _ledger_email = $(document).find("._ledger_email").val();
    var _ledger_credit_limit = $(document).find("._ledger_credit_limit").val();
    var _ledger_is_user = $(document).find("._ledger_is_user").val();
    var _ledger_is_sales_form = $(document).find("._ledger_is_sales_form").val();
    var _ledger_is_purchase_form = $(document).find("._ledger_is_purchase_form").val();
    var _ledger_is_all_branch = $(document).find("._ledger_is_all_branch").val();
    var _ledger_status = $(document).find("._ledger_status").val();
    var opening_cr_amount = $(document).find(".opening_cr_amount").val();
    var opening_dr_amount = $(document).find(".opening_dr_amount").val();

    var reqired_fields = 0;
    if(_account_head_id ==""){
       $(document).find('._account_head_id').addClass('required_border');
       reqired_fields =1;
    }else{
      $(document).find('._account_head_id').removeClass('required_border');
    }
    if(_account_groups ==""){
       $(document).find('._account_groups').addClass('required_border');
       reqired_fields =1;
    }else{
      $(document).find('._account_groups').removeClass('required_border');
    }
    if(_ledger_name ==""){
       $(document).find('._ledger_name').addClass('required_border');
       reqired_fields =1;
    }else{
      $(document).find('._ledger_name').removeClass('required_border');
    }
    if(reqired_fields ==1){
      return false;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
var action_url = project_base_url+"/"+'ajax-ledger-save';
        $.ajax({
           type:'POST',
           url:action_url,
           data:{_account_head_id,_account_groups,_ledger_branch_id,_ledger_name,_ledger_address,_ledger_code,_ledger_short,_ledger_nid,_ledger_phone,_ledger_email,_ledger_credit_limit,_ledger_is_user,_ledger_is_sales_form,_ledger_is_purchase_form,_ledger_is_all_branch,_ledger_status,opening_cr_amount,opening_dr_amount,_ledger_organization_id,_ledger_cost_center_id
           },
           success:function(data){
             console.log(data)
              if(data !=""){
                if(data==0){
                    alert("This Name has been already Taken");
                }else{
                  alert("Information Save Successfully");
                  $(document).find("._ledger_modal_form").trigger('reset');
                  $(document).find(".ledgerEntryModal").click();
                }
                
                
              }else{
                alert("Information Not Save");
              }

           }

        });

  })

$(document).on('click','.inventoryEntryModal',function(){
    $(document).find("#exampleModalLong_item").modal("hide");
})

$(document).on('click','.ledgerEntryModal',function(){
    $(document).find("#exampleModalLong").modal("hide");
})
$(document).on('click','.duplicateBarcodeModalclose',function(){
    $(document).find("#duplicateBarcodeModal").modal("hide");
})
$(document).on('click','.exampleModalClose',function(){
    $(document).find("#exampleModal").modal("hide");
})


function after_request_date__today(_date){
            var data = _date.split('-');
            var yyyy =data[0];
            var mm =data[1];
            var dd =data[2];
            if(default_date_formate=='DD-MM-YYYY'){
              return (dd[1]?dd:"0"+dd[0]) +"-"+ (mm[1]?mm:"0"+mm[0])+"-"+ yyyy ;
            }
            if(default_date_formate=='MM-DD-YYYY'){
              return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]) +"-"+  yyyy ;
            }
            

            
          }


   $(document).on("click","._action_button_detail",function(){
      var _id = $(this).attr('attr_id');
       var _show_detils= $(document).find('._action_button__'+_id).hasClass('_show_detils');
       var _type=$(this).attr('attr_type');
      if(_show_detils ==false){
        $(document).find('._action_button__'+_id).addClass('_show_detils');
            $.ajaxSetup({  headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  } });
var action_url = project_base_url+"/"+'master-base-detils';
            $.ajax({
               type:'POST',
               url:action_url,
               data:{_id, _type},
               dataType:'HTML',
               success:function(data){
                  $(document).find("._details_show__"+_id).html(data);

               }

            });
      }
    })



 function printDiv(divID) {
            var divElements = document.getElementById(divID).innerHTML;
            var oldPage = document.body.innerHTML;
            document.body.innerHTML ="<html><head><title></title></head><body>" +
                divElements + "</body>";
            window.print();
            document.body.innerHTML = oldPage;
           // location.reload();
        }
     function fnExcelReport() {
      var tab_text= $(document).find("#printablediv").html();
      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE "); 
      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
      {
        document.open("txt/html","replace");
        document.write(tab_text);
        document.close(); 
        sa=document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
      }  
      else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

      return (sa);
    }  


    function _lock_action(_id,_action,_table_name){
       $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
       var action_url = project_base_url+"/"+'_lock_action';
        $.ajax({
           type:'POST',
           url:action_url,
           data:{_id,_action,_table_name},
           success:function(data){
              console.log(data);
           }
        });
    }  


    function isEmpty(value){
  if ( value === 'undefined' || value =="" || value =="null" || value ==null || value ==undefined) {
        return  value = "";
    }else{
      return value;
    }
}
 


    
    $(document).on('click',"input[type='number']",function(){
        $(this).select();
    })
    // $(document).on('click',"input[type='text']",function(){
    //     $(this).select();
    // })

    $(document).on('change',"#_unique_barcode",function(){
      var _check_val = $(this).val();
      if(_check_val ==1){
        $(document).find("#_barcode").val("");
        $(document).find("#_barcode").attr("readonly",true);
      }else{
        $(document).find("#_barcode").attr("readonly",false);
      }

    })
   
$(function(){
 // $(document).find("._pushmenu").click();
 // $(document).find(".display_none").hide();
})




//Role and permission section

$(document).on('change','[name="group_check"]',function(){
    var class_name = $(this).attr('class');
    if ($(this).is(':checked')) {
      $(document).find(`.${class_name}`).attr('checked',true);
    }else{
      $(document).find(`.${class_name}`).attr('checked',false);
    }
})

$(document).on('change','[name="all_all_check"]',function(){
    var class_name = 'all_check';
    if ($(this).is(':checked')) {
      $(document).find(`.${class_name}`).attr('checked',true);
    }else{
      $(document).find(`.${class_name}`).attr('checked',false);
    }
})
