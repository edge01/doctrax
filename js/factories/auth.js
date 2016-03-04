myApp.factory('auth', function($cookies, $http) {
    var access_token = $cookies.get('access_token');
    var username = $cookies.get('username');
    var office = $cookies.get('office');
    return {
        logout: function() {

            var logoutYes = bootbox.dialog({
                message: "Are you sure you want to logout " + username + "?",
                title: "Logout",
                buttons: {
                    danger: {
                        label: "No",
                        className: "btn-danger",
                        callback: function() {

                        }
                    },
                    main: {
                        label: "Yes",
                        className: "btn-primary",
                        callback: function(logoutYes) {
                            if (logoutYes) {
                                $cookies.put('access_token', '');
                                $cookies.put('username', '');
                                $cookies.put('office', '');
                                window.location.href = '/doctrax/';
                            }
                            return true;
                        }
                    }
                }
            });
        },
        checkLogin: function() {


            var response = $http.get(
                "API/check-auth.php?rand=" + new Date()
                .getTime() + "&access_token=" + access_token + "&username=" + username + "&office=" + office);
            response.success(function(data, status, headers, config) {
                if (!data.success) {
                    alert("User not login!");
                    window.location.href = '/doctrax/';
                }
            });
            response.error(function(data, status, headers, config) {
                alert("AJAX failed!");
            });
            return true;
        }

    };
});