angular.module('controllers.parts', []).controller('PartsNewCtrl', ['$scope', 'PartsSvc', function ($scope, PartsSvc) {
    $scope.part = {
        _bike_ID: $scope.$parent.ID
    };
    $scope.submit = function () {
        PartsSvc.addPart($scope.part).then(function () {

        });
    };
}]);

angular.module('controllers.parts').controller('PartsCtrl', ['$scope', 'PartsSvc', function ($scope, PartsSvc) {
    $scope.parts = [];

    PartsSvc.getParts().then(function (parts) {
        $scope.parts = parts;
    });
}]);