angular.module('myApp', []).controller('userCtrl', function($scope, $http, $location) {

  $scope.init = function(){


  $scope.searchString = "";
  // $scope.searchUser();


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

  $("#formShow").submit(function(e){
    e.preventDefault();
    $("#portfolioModal2").modal("show");
    
    return false;
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
 
  
}

$scope.clearSearch = function(){
  $scope.searchString= "";

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
      .getTime() + "&search=" + $scope.getSearchString());

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

angular.bootstrap(document.getElementById("app2"), ["myApp"]);