/**
 * Created by Evgeniy on 08.10.2016.
 */
var module = angular.module('viewerApp', [])
    .controller('viewerCtl', ['$scope', '$http', function ($scope, $http) {
        var vCtl = this;

        vCtl.Model = {};

        var queryString = function () {
            var query_string = {};
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                // If first entry with this name
                if (typeof query_string[pair[0]] === "undefined") {
                    query_string[pair[0]] = decodeURIComponent(pair[1]);
                    // If second entry with this name
                } else if (typeof query_string[pair[0]] === "string") {
                    var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
                    query_string[pair[0]] = arr;
                    // If third or later entry with this name
                } else {
                    query_string[pair[0]].push(decodeURIComponent(pair[1]));
                }
            }
            return query_string;
        }();

        vCtl.Refresh = function() {
            var id = queryString.id;
            $http.get("models.php?id=" + id).then(function (response) {
                console.log(response);
                vCtl.Model = response.data;


            });
        };

        vCtl.Refresh();
    }]);