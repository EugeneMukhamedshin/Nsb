angular.module('adminApp', ['angularFileUpload'])
    .controller('adminController', ['$scope', '$http', 'FileUploader', function ($scope, $http, FileUploader) {
        var admCtrl = this;

        admCtrl.Page = 1;
        admCtrl.IsLastPage = false;
        admCtrl.IsFirstPage = false;
        admCtrl.PageSize = 10;
        admCtrl.SelectedModel = {};
        admCtrl.IsWorking = false;
        admCtrl.ZipProgress = 0;

        var uploader = $scope.uploader = new FileUploader({
            url: 'upload.php'
        });

        uploader.onAfterAddingFile = function (fileItem) {
            console.info('onAfterAddingFile', fileItem);
            fileItem.formData.push({id: admCtrl.SelectedModel.Model.Id});
            if (fileItem.file.name.indexOf('.obj') != -1) {
                var zip = new JSZip();
                zip.file(fileItem.file.name, fileItem._file);
                admCtrl.IsWorking = true;
                zip.generateAsync({type: 'blob', compression: 'DEFLATE'}, function updateCallback(metadata) {
                    $scope.$apply(function () {
                        admCtrl.ZipProgress = metadata.percent.toFixed(2);
                        console.log(admCtrl.ZipProgress + '%');
                    });
                }).then(function (blob) {
                    fileItem._file = blob;
                    $scope.$apply(function () {
                        admCtrl.IsWorking = false;
                    });
                });
            }
        };

        uploader.onBeforeUploadItem = function (fileItem) {
            console.info(fileItem);
        };

        uploader.onCompleteItem = function (fileItem, response, status, headers) {
            admCtrl.IsWorking = !uploader.getNotUploadedItems().length;
        };

        uploader.onCompleteAll = function () {
            admCtrl.IsWorking = !uploader.getNotUploadedItems().length;
        };

        admCtrl.Refresh = function () {
            admCtrl.models = [];
            $http.get("models.php?page=" + admCtrl.Page + "&pageSize=" + admCtrl.PageSize).then(function (response) {
                console.log(response);
                var models = response.data.models;
                admCtrl.IsFirstPage = admCtrl.Page == 1;
                admCtrl.IsLastPage = models.length != admCtrl.PageSize + 1;
                if (!admCtrl.IsLastPage)
                    models.pop();
                models.forEach(function (model) {
                    var vm = new viewModel(model);
                    admCtrl.models.push(vm);
                });
                if (models.length > 0)
                    admCtrl.SelectModel(admCtrl.models[0]);
            });
        };

        admCtrl.PrevPage = function () {
            if (admCtrl.Page == 1)
                return;
            admCtrl.Page--;
            admCtrl.Refresh(admCtrl.Page);
        };

        admCtrl.NextPage = function () {
            if (admCtrl.models.length < admCtrl.PageSize)
                return;
            admCtrl.Page++;
            admCtrl.Refresh(admCtrl.Page);
        };

        admCtrl.SelectModel = function (model) {
            if (admCtrl.SelectedModel)
                admCtrl.SelectedModel.IsSelected = false;
            admCtrl.SelectedModel = model;
            admCtrl.SelectedModel.IsSelected = true;
        };

        admCtrl.AddModel = function () {
            $http
                .get("add.php")
                .then(function (response) {
                    console.log(response);
                    var vm = new viewModel(response.data.model);
                    admCtrl.models.shift();
                    admCtrl.models.push(vm);
                    admCtrl.SelectModel(vm);
                })
        };

        admCtrl.DeleteSelected = function () {
            var selected = _.filter(admCtrl.models, function (model) {
                return model.IsChecked;
            });
            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            };
            $http
                .post("delete.php", selected, config)
                .success(function () {
                    if (selected.length == admCtrl.models.length && admCtrl.Page > 1)
                        admCtrl.Page--;
                    admCtrl.Refresh();
                })
                .error(function () {
                    admCtrl.Refresh();
                });
        };

        function UploadBlob(fileName, blob) {
            console.log('Uploading Blob ' + fileName);
            var fd = new FormData();
            fd.append('id', admCtrl.SelectedModel.Id);
            fd.append('blob', blob, fileName);
            var request = new XMLHttpRequest();
            request.open('POST', 'upload.php', true);
            request.onprogress = function (evt) {
                console.log(evt);
            };
            request.onload = function (evt) {
                console.log(evt);
                console.log('Blob ' + fileName + ' uploaded succesfully');
            };
            request.send(fd);
        }

        function UploadFile(file) {
            console.log('Uploading File ' + file.name);
        }

        function HandleFile(file) {
            if (file.name.indexOf('.obj') != -1) {
                var zip = new JSZip();
                zip.file(file.name, file);
                zip.generateAsync({type: "blob"})
                    .then(function (blob) {
                        UploadBlob(file.name, blob);
                    });
            }
            else {
                UploadFile(file);
            }
        }

        $scope.HandleFiles = function (input) {
            console.log(input.files);

            for (var i = 0; i < input.files.length; i++) {
                HandleFile(input.files[i]);
            }
        };

        admCtrl.Refresh();
    }]);