angular.module('controllers.routes', []).controller('RoutesCtrl', ['$scope', 'RoutesSvc', function ($scope, RoutesSvc) {
    $scope.routes = [];

    RoutesSvc.getUserRoutes().then(function (routes) {
        $scope.routes = routes;
    });
}]);

angular.module('controllers.routes').controller('RoutesNewCtrl', ['$scope', '$location', 'RoutesSvc', 'BikesSvc', function ($scope, $location, RoutesSvc, BikesSvc) {
    $scope.route = {
        landmarks: [],
        comments: []
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

angular.module('controllers.routes').controller('RoutesShowCtrl', ['$scope', 'RoutesSvc', function ($scope, RoutesSvc) {
    $scope.route = {};
    $scope.new_comment = {};

    function initNewComment (){
        $scope.new_comment = {
            route_ID: $scope.route.ID
        };
    }

    if (angular.isDefined($scope.$parent.ID) && !isNaN($scope.$parent.ID)) {
        RoutesSvc.getUserRoutes({id: $scope.$parent.ID, _landmarks: true, _comments: true}).then(function (routes) {
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
            $scope.route.comments.unshift(angular.copy($scope.new_comment));
            initNewComment();
        })
    }
}]);
