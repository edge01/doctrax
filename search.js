var myApp = angular.module('myApp', ['ngCookies']);

myApp.controller('userCtrl', function($scope,
  $http, $cookies, $location, auth) {

  $scope.init = function(){

    $scope.access_token = $cookies.get('access_token');
    $scope.username = $cookies.get('username');
    $scope.office = $cookies.get('office');
    auth.checkLogin();
    $scope.auth = auth;
   
    $scope.searchString = "";
  
    $scope.getMail();
    getDoc();

    $('div[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'bottom'
    });

    $("#barcode").keydown(function (event) {
        // http://stackoverflow.com/questions/25198522/typing-to-hidden-input-field
          
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
                // event.preventDefault();
                
            } else {
                // Ensure that it is a number and stop the keypress
                if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                    
                    event.preventDefault();
                    $('#barcode').tooltip('show');
                    // $('#barcode').val("");
                }else{
                  $('#barcode').tooltip('hide');

                }
              
            }
             

        });


  var input = $('#barcode');
    var button = $('#barbutton');
  
   setInterval(function(){
    if(input.val().length > 0){
        button.attr('disabled', false);
    }else{
        button.attr('disabled', true);
    }
}, 100);


    $("#formShow").submit(function(e){
    e.preventDefault();
    $("#portfolioModal2").modal("show");
    
    return false;
});


}

$scope.PrintElem = function(elem)
    {
        Popup($(elem).html());
        // $("#barImg").modal("hide");

    }

    function Popup(data)
    {
        var mywindow = window.open('', 'print barcode', 'height=400,width=600');
        mywindow.document.write('<html><head><title>Tracking Number</title>');
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.print();
        mywindow.close();
        return true;
    }

function getDoc() {
    var response = $http.get(
      "API/location.php?rand=" + new Date().getTime() + "&access_token=" + $scope.access_token);

    response.success(function(data, status, headers, config) {
      // console.log(data.loc);
      // $scope.loc = data.loc;
      if(data.success){
        // console.log(data.loc);
        $scope.loc = data.Manila;
        $scope.mak = data.Makati;
        $scope.mal = data.Malolos;
        $scope.doc = data.docu_type;

        setTimeout(function(){
          $scope.$apply();  
          $('.selectpicker').selectpicker();          
        },1);
        
      } else {
        alert(data.message);
        window.location.href = 'index.html';
      }

    });
    response.error(function(data, status, headers, config) {
      alert("AJAXer failed!");
    });
  }


$scope.getMail = function() {

    var response = $http.get(
      "API/mail.php?rand=" + new Date()
      .getTime() + "&access_token=" + $scope.access_token + "&username=" + $scope.username + "&officenigga=" + $scope.office);

    response.success(function(data, status, headers, config) {
      
      if(data.success){
        $scope.mail = data.mail;
  
            
      } else {
        alert(data.message);
        window.location.href = '/doctrax/';
             }
    });
    response.error(function(data, status, headers, config) {
      alert("AJAX failed!");
    });
  }

$scope.clearForw = function(){
    $("#submitBarer").css("visibility", "hidden");
    $("#formBinder").bind("change", function() { 
        if ($(".selectpicker").val().length > 0) {
           $("#submitBarer").css("visibility", "visible");
        } else {
           $("#submitBarer").css("visibility", "hidden");
        }
    });
  $('.selectpicker').val("").selectpicker('refresh');

}

$scope.clearBar = function(){
  $("#submitBar").css("visibility", "hidden");

    $("#formBind").bind("change", function() { 
        if ($(".selectpicker").val().length > 0 && $(".selectpicker1").val().length > 0) {
           $("#submitBar").css("visibility", "visible");
        } else {
           $("#submitBar").css("visibility", "hidden");
        }
    });


  $("#others[type=text]").hide();

$(".selectpicker1").change(function() {
    
    var val = $(this).val();
    $("#others[type=text]").hide();

    if( val == "Others" ) {
        $("#others").show();  
        $("#others").val("");  
         $('.selectpicker1').selectpicker('hide');
    }
}) 
  $('.selectpicker1').selectpicker('show');
  $('.selectpicker').val("").selectpicker('refresh');
  $('.selectpicker1').val("").selectpicker('refresh');

}

$scope.addUser = function(){
  $("#submitUser").css("visibility", "hidden");

    $("#formUser").bind("change", function() { 
        if ($(".selectpicker3").val().length > 0 && $("#uname").val().length > 0 && $("#pword").val().length > 0) {
           $("#submitUser").css("visibility", "visible");
        } else {
           $("#submitUser").css("visibility", "hidden");
        }
    });

  $('.selectpicker3').val('').selectpicker('refresh');
  $scope.uname = '';
  $scope.pword = '';
  
}

$scope.addMail = function(){
  $("#submitBare").css("visibility", "hidden");

    $("#formBinde").bind("change", function() { 
        if ($(".selectpicker").val().length > 0 && $("#msub").val().length > 0 && $("#mmes").val().length > 0) {
           $("#submitBare").css("visibility", "visible");
        } else {
           $("#submitBare").css("visibility", "hidden");
        }
    });

  $('.selectpicker').val('').selectpicker('refresh');
  
 
  $scope.msub = '';
  $scope.mmes = '';
  
}

$scope.confirmBar = function(){
  $scope.trackingNo = '';
  
}

$scope.clearSearch = function(){
  $scope.searchString= "";

}

$scope.showMail = function(id) {
  $scope.mto = $scope.mail[id].sender.toString();
  $scope.msub = $scope.mail[id].subject.toString();
  $scope.mmes = $scope.mail[id].message.toString();
}

$scope.insertUser = function() {
    $http.post("API/create-user.php", {
      'ofice': $scope.ofice,
      'user' : $scope.username,
      'uname': $scope.uname,
      'pword': $scope.pword,
      'access_token': $scope.access_token
    }).success(function(data, status, headers, config) {
      console.log(data);
      if(data.success){
        $("#addUser").modal("hide");
       // $scope.getMail();
       bootbox.alert("Creating user success!");     
      
      } else {
        bootbox.alert(data.message);
        $("#addUser").modal("hide");
      }
     
      
    });
  }

$scope.insertDocument = function() {
    $http.post("API/barcode.php", {
      'sender': $scope.office,
      'receiver': $scope.receiver,
      'doctype': $scope.doctypes,
      'access_token': $scope.access_token
    }).success(function(data, status, headers, config) {
      console.log(data);
      if(data.success){
        $("#barcodegen").modal("hide");
       $scope.getMail();
       // bootbox.alert("Docu Generate Success");
       $("#barcodegen").modal("hide");
       $("#barImg").modal("show");   
       $scope.barid = data.barid;  
        // window.open("https://www.barcodesinc.com/generator/image.php?code="+ data.barid + "&style=197&type=C128A&width=230&height=50&xres=1&font=5",'asdas', 'toolbars=0,width=500,height=500,left=200,top=200,scrollbars=1,resizable=1');
    
      } else {
        bootbox.alert(data.message);
        $("#barcodegen").modal("hide");
      }
    });
  }

$scope.insertMail = function() {
    $http.post("API/insert-docu.php", {
      'msender': $scope.office,
      'mto': $scope.mto,
      'msub': $scope.msub,
      'mmes': $scope.mmes,
      'access_token': $scope.access_token
    }).success(function(data, status, headers, config) {
      console.log(data);
      if(data.success){
       $scope.getMail();
       bootbox.alert("Message has been sent!"); 
      } else {
        bootbox.alert(data.message);
        $("#composeModal").modal("hide");
      }
    });
  }

 $scope.updateMail = function() {
    $http.post("API/update-docu.php", {
      'trackingNo': $scope.trackingNo,
      'offname': $scope.office,
      'access_token': $scope.access_token
    }).success(function(data, status, headers, config) {
      console.log(data);
      if(data.success){
       $scope.getMail();
       bootbox.alert("Tracking Number received!");
       $("#confirmModal").modal("hide");
      } else {
        bootbox.alert(data.message);
        $("#confirmModal").modal("hide");
      }
    });
  }

   $scope.deleteMail = function(id) {
    
    if (confirm("Do you want to delete this data?") == true) {
      $http.post("API/delete-docu.php", {
        'rand': new Date().getTime(),
        'id': id,
        'access_token': $scope.access_token
      }).success(function(data, status, headers, config) {
        if(data.success){
          bootbox.alert("Mail successfully deleted!");
    
          $scope.getMail();
        } else {
          alert(data.message);
          window.location.href = '/doctrax/';
        }

        //popup here
      });
  }
}

  $scope.getSearchString = function(){

    var querySearch = (($location.search().search) ? $location.search().search : '');

    if($scope.searchString != '' && $scope.searchString != undefined){
      return $scope.searchString;
    } else if( querySearch != '' ){
      return querySearch;
    } else {
      return '';
    }
  }

$scope.searchUser = function() {

    var response = $http.get(
      "API/search.php?rand=" + new Date()
      .getTime() + "&search=" + $scope.getSearchString() + "&access_token=" + $scope.access_token + "&username=" + $scope.username);

    response.success(function(data, status, headers, config) {
      
      if(data.success){
        $scope.doxs = data.doxs;
        $scope.message="";
        $scope.searchString= "";
  
            
      } else {
        $scope.message = data.message;
        $scope.doxs= "";
        $scope.searchString= "";
        
             }
    });
    response.error(function(data, status, headers, config) {
      alert("AJAX failed!");
    });
  }

 $scope.init();



});
