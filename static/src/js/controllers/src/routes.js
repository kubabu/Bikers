angular.module('controllers.routes', []).controller('RoutesCtrl', ['$scope', 'RoutesSvc', function ($scope, RoutesSvc) {
    $scope.routes = [];

    $scope.getRoutes = function () {
        RoutesSvc.getUserRoutes().then(function (routes) {
            $scope.routes = routes;
        });
    };

    $scope.delete = function (id) {
        RoutesSvc.deleteUserRoute(id).then(function () {
            $scope.getRoutes();
        });
    };

    $scope.getRoutes();
}]);

angular.module('controllers.routes').controller('RoutesEditCtrl', ['$scope', '$location', 'RoutesSvc', 'BikesSvc', 'UsersSvc', function ($scope, $location, RoutesSvc, BikesSvc, UsersSvc) {
    $scope.route = {}
    $scope.bikes = [];

    if (angular.isDefined($scope.$parent.ID) && !isNaN($scope.$parent.ID)) {
        RoutesSvc.getUserRoutes({id: $scope.$parent.ID, _landmarks: true}).then(function (routes) {
            $scope.route = routes[0];
            $scope.route.duration_of_ride = moment({
                minutes: $scope.route.duration_of_ride
            }).toDate();

            $scope._set_date = moment($scope.route.date_of_ride).toDate();
            $scope._set_hour = moment($scope.route.date_of_ride).toDate();

            BikesSvc.getBikes().then(function (bikes) {
                $scope.bikes = bikes;

                if (bikes.length > 0 && !$scope.route.bike_ID) {
                    $scope.route.bike_ID = bikes[0].ID;
                }
            });
        });
    }

    $scope.submit = function () {
        $scope.route.date_of_ride = moment($scope._set_date).format("YYYY-MM-DD ") +  moment($scope._set_hour).format("HH:mm:ss");
        $scope.route.duration_of_ride = $scope.route.duration_of_ride.getMinutes();

        RoutesSvc.editUserRoute($scope.route).then(function (res) {
            if(res.length > 0){
                $location.path('/routes/show/' + res[0]);
            } else {
                $location.path('/routes/');
            }
        });
    };
}]);

angular.module('controllers.routes').controller('RoutesShowCtrl', ['$scope', '$location', 'RoutesSvc', 'UsersSvc', function ($scope, $location, RoutesSvc, UsersSvc) {
    $scope.route = {};
    $scope.new_comment = {};

    function initNewComment (){
        $scope.new_comment = {
            route_ID: angular.copy($scope.route.ID)
        };
    }

    $scope.delete = function (id) {
        RoutesSvc.deleteUserRoute(id).then(function () {
            $location.path('/routes/');
        });
    };


    UsersSvc.getUsers({"_me": true}).then(function(resp){
        if(angular.isArray(resp) && resp.length > 0) {
            $scope.cur_user = resp[0];
        }
    });

    if (angular.isDefined($scope.$parent.ID) && !isNaN($scope.$parent.ID)) {
        RoutesSvc.getUserRoutes({id: $scope.$parent.ID, _landmarks: true, _comments: true, _comments_users: true}).then(function (routes) {
            $scope.route = routes[0];
            initNewComment();

            if(angular.isArray($scope.route.comments) && $scope.route.comments.length > 0) {
                $scope.route.comments = $scope.route.comments.map(function(comment){
                    comment.date_create =  moment(comment.date_create).valueOf();
                    return comment;
                })
            }
        });
    }

    $scope.addComment = function(){
        RoutesSvc.addRouteComment($scope.new_comment).then(function (){
            $scope.new_comment.date_create = new Date();
            $scope.new_comment.first_name = $scope.cur_user.first_name;
            $scope.new_comment.last_name = $scope.cur_user.last_name + " (Ty)";

            $scope.route._comments.unshift(angular.copy($scope.new_comment));
            initNewComment();
        })
    };
}]);

angular.module('controllers.routes').controller('RoutesNewCtrl', ['$scope', '$location', 'RoutesSvc', 'BikesSvc', function ($scope, $location, RoutesSvc, BikesSvc) {
    $scope.route = {
        _landmarks: [],
        _comments: []
    };
    $scope.route.duration_of_ride = new Date(0);
    $scope.bikes = [];

    $scope._set_date = new Date();
    $scope._set_hour = new Date();

    BikesSvc.getBikes().then(function (bikes) {
        $scope.bikes = bikes;

        if (bikes.length > 0) {
            $scope.route.bike_ID = bikes[0].ID;
        }
    });

    $scope.submit = function () {
        $scope.route.date_of_ride = moment($scope._set_date).format("YYYY-MM-DD ") +  moment($scope._set_hour).format("HH:mm:ss");
        $scope.route.duration_of_ride = $scope.route.duration_of_ride.getMinutes();

        RoutesSvc.addRoute($scope.route).then(function (res) {
            if(res.length > 0){
                $location.path('/routes/show/' + res[0]);
            } else {
                $location.path('/routes/');
            }

        });
    };
}]);
