angular.module('controllers.bikes', []).controller('BikesCtrl', ['$scope', 'BikesSvc', function ($scope, BikesSvc) {
    $scope.bikes = [];

    BikesSvc.getBikes().then(function (bikes) {
        $scope.bikes = bikes;
    });
}]);

angular.module('controllers.bikes').controller('BikesNewCtrl', ['$scope', 'BikesSvc', function ($scope, BikesSvc) {
    $scope.bike = {};
    $scope.submit = function () {
        BikesSvc.addBike($scope.bike).then(function () {

        });
    }
}]);

angular.module('controllers.bikes').controller('BikesShowCtrl', ['$scope', function ($scope) {
    $scope.bike = {
        name:"Romet Osa",
        description:"Składak z koralikami na kołach",
        parts:[
            {name:"koralik1"},
            {name:"koralik2"}
        ]
    };
}]);
