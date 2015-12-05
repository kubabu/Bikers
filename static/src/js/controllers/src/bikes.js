angular.module('controllers.bikes', []).controller('BikesCtrl', ['$scope', function ($scope) {
    $scope.bikes = [{
        name: 'Unibike Cruzeo 29x11'
    }];
}]);

angular.module('controllers.bikes').controller('BikesNewCtrl', ['$scope', function ($scope) {
    $scope.bike = {};
    $scope.submit = function () {

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
