angular.module('loginApp', ['ngCookies']).controller('loginCtrl', function($scope, $cookies, $http, $window) {

  $cookies.put('access_token', '');
   $cookies.put('username', ''); 
   $cookies.put('office', ''); 
  $scope.authenticate = function(id) {

    $http.post(APIURL + "authentication.php?rand=" + new Date().getTime(),{
      'username' : $scope.username,
      'password' : $scope.password,
      'office' : $scope.office,
      'access_token' : $scope.access_token
    })
    .success(function(data,status,headers,config){
        console.log(data);

        if (data.user){
		      $cookies.put('access_token', data.user.access_token);
          $cookies.put('username', data.user.username);
          $cookies.put('office', data.user.office);
          window.location.href = 'main.html';
        } 

        else  {
          $scope.message = data.message;
          $scope.username = '';
          $scope.password = '';
          $('#username').focus();
        }
    });
  }

 
});
 angular.bootstrap(document.getElementById("app1"), ["loginApp"]);


