angular.module('controllers.routes', []).controller('RoutesCtrl', ['$scope', 'RoutesSvc', function ($scope, RoutesSvc) {
    $scope.routes = [];

    RoutesSvc.getUserRoutes().then(function (routes) {
        $scope.routes = routes;
    });
}]);

angular.module('controllers.routes').controller('RoutesNewCtrl', ['$scope', 'RoutesSvc', 'BikesSvc', function ($scope, RoutesSvc, BikesSvc) {
    $scope.route = {
        landmarks: [],
        comments: []
    };
    $scope.bikes = [];

    BikesSvc.getBikes().then(function (bikes) {
        $scope.bikes = bikes;

        if (bikes.length > 0) {
            $scope.route.bike_ID = bikes[0].ID;
        }
    });

    $scope.submit = function () {
        RoutesSvc.addRoute($scope.route).then(function () {

        });
    };
}]);

angular.module('controllers.routes').controller('RoutesShowCtrl', ['$scope', 'RoutesSvc', function ($scope, RoutesSvc) {
    $scope.route = {};

    if (angular.isDefined($scope.$parent.ID) && !isNaN($scope.$parent.ID)) {
        RoutesSvc.getUserRoutes({id: $scope.$parent.ID, _landmarks: true, _comments: true}).then(function (routes) {
            $scope.route = routes[0];
        });
    }
}]);
