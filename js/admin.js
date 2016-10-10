var module = angular.module('adminApp', ['angularFileUpload'])
    .factory('shareService', function ($rootScope) {
        var shareService = {};

        shareService.selectModel = function (model) {
            $rootScope.$broadcast('selectModel', model)
        };

        return shareService;
    })
    .controller('modelController', ['$scope', '$http', 'shareService', function ($scope, $http, shareService) {
        var modelCtl = this;

        modelCtl.Page = 1;
        modelCtl.PageSize = 10;
        modelCtl.IsLastPage = false;
        modelCtl.IsFirstPage = false;

        modelCtl.SelectedItem = {};

        modelCtl.IsWorking = false;

        modelCtl.models = [];

        modelCtl.Refresh = function () {
            $http.get("models.php?page=" + modelCtl.Page + "&pageSize=" + modelCtl.PageSize).then(function (response) {
                console.log(response);
                var models = response.data.models;
                modelCtl.IsFirstPage = modelCtl.Page == 1;
                modelCtl.IsLastPage = models.length != modelCtl.PageSize + 1;
                if (!modelCtl.IsLastPage)
                    models.pop();
                modelCtl.models = [];
                models.forEach(function (model) {
                    var vm = new viewModel(model);
                    modelCtl.models.push(vm);
                });
                if (models.length > 0)
                    modelCtl.SelectItem(modelCtl.models[0]);
                else
                    modelCtl.SelectedItem = {};
            });
        };

        modelCtl.PrevPage = function () {
            if (modelCtl.Page == 1)
                return;
            modelCtl.Page--;
            modelCtl.Refresh();
        };

        modelCtl.NextPage = function () {
            if (modelCtl.models.length < modelCtl.PageSize)
                return;
            modelCtl.Page++;
            modelCtl.Refresh();
        };

        modelCtl.SelectItem = function (model) {
            if (modelCtl.IsWorking)
                return;
            if (modelCtl.SelectedItem) {
                modelCtl.SelectedItem.IsSelected = false;
            }
            modelCtl.SelectedItem = model;
            modelCtl.SelectedItem.IsSelected = true;
            shareService.selectModel(model.Model);
        };

        modelCtl.AddModel = function () {
            $http
                .get("add.php")
                .then(function (response) {
                    console.log(response);
                    var vm = new viewModel(response.data);
                    if (modelCtl.models.length == modelCtl.PageSize)
                        modelCtl.models.shift();
                    modelCtl.models.push(vm);
                    modelCtl.SelectItem(vm);
                })
        };

        modelCtl.DeleteSelected = function () {
            var selected = _.filter(modelCtl.models, function (model) {
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
                    if (selected.length == modelCtl.models.length && modelCtl.Page > 1)
                        modelCtl.Page--;
                    modelCtl.Refresh();
                })
                .error(function () {
                    modelCtl.Refresh();
                });
        };

        modelCtl.UpdateModel = function(model) {
            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            };
            $http
                .post("update.php", model, config)
                .success(function (response) {
                    console.log(response);
                    modelCtl.Refresh();
                })
                .error(function () {
                    modelCtl.Refresh();
                });
        };

        modelCtl.Refresh();
    }])
    .controller('modelFileController', ['$scope', '$http', 'FileUploader', 'shareService', function ($scope, $http, FileUploader, shareService) {
        var modelFileCtl = this;

        modelFileCtl.Page = 1;
        modelFileCtl.PageSize = 10;
        modelFileCtl.IsLastPage = false;
        modelFileCtl.IsFirstPage = false;

        modelFileCtl.SelectedItem = {};

        modelFileCtl.IsWorking = false;

        modelFileCtl.AllAreChecked = false;

        modelFileCtl.model = {};
        modelFileCtl.modelFiles = [];

        modelFileCtl.Refresh = function () {
            if (!modelFileCtl.model) {
                modelFileCtl.modelFiles = [];
                return;
            }
            $http.get("model_files.php?modelId=" + modelFileCtl.model.Id + "&page=" + modelFileCtl.Page + "&pageSize=" + modelFileCtl.PageSize).then(function (response) {
                console.log(response);
                var modelFiles = response.data.modelFiles;
                modelFileCtl.IsFirstPage = modelFileCtl.Page == 1;
                modelFileCtl.IsLastPage = modelFiles.length != modelFileCtl.PageSize + 1;
                if (!modelFileCtl.IsLastPage)
                    modelFiles.pop();
                modelFileCtl.modelFiles = [];
                modelFiles.forEach(function (modelFile) {
                    var vm = new viewModel(modelFile);
                    modelFileCtl.modelFiles.push(vm);
                });
                if (modelFiles.length > 0)
                    modelFileCtl.SelectItem(modelFileCtl.modelFiles[0]);
            });
        };

        modelFileCtl.PrevPage = function () {
            if (modelFileCtl.Page == 1)
                return;
            modelFileCtl.Page--;
            modelFileCtl.Refresh();
        };

        modelFileCtl.NextPage = function () {
            if (modelFileCtl.modelFiles.length < modelFileCtl.PageSize)
                return;
            modelFileCtl.Page++;
            modelFileCtl.Refresh();
        };

        modelFileCtl.SelectItem = function (modelFile) {
            if (modelFileCtl.IsWorking)
                return;
            if (modelFileCtl.SelectedItem) {
                modelFileCtl.SelectedItem.IsSelected = false;
            }
            modelFileCtl.SelectedItem = modelFile;
            modelFileCtl.SelectedItem.IsSelected = true;
        };

        modelFileCtl.ToggleAllAreChecked = function () {
            var flag = modelFileCtl.AllAreChecked;
            modelFileCtl.modelFiles.forEach(function (item) {
                item.IsChecked = flag;
            });
            modelFileCtl.AllAreChecked = flag;
        };

        // modelFileCtl.AddModelFiles = function () {
        //     $http
        //         .get("add.php")
        //         .then(function (response) {
        //             console.log(response);
        //             var vm = new viewModel(response.data.models[0]);
        //             modelFileCtl.models.shift();
        //             modelFileCtl.models.push(vm);
        //             modelFileCtl.SelectItem(vm);
        //             //shareService.newModel();
        //         })
        // };
        //
        modelFileCtl.DeleteSelected = function () {
            var selected = _.filter(modelFileCtl.modelFiles, function (modelFile) {
                return modelFile.IsChecked;
            });
            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            };
            $http
                .post("delete_files.php", selected, config)
                .success(function () {
                    if (selected.length == modelFileCtl.modelFiles.length && modelFileCtl.Page > 1)
                        modelFileCtl.Page--;
                    modelFileCtl.AllAreChecked = false;
                    modelFileCtl.Refresh();
                })
                .error(function () {
                    modelFileCtl.Refresh();
                });
        };

        var uploader = $scope.uploader = new FileUploader({
            url: 'upload.php'
        });

        uploader.onAfterAddingFile = function (fileItem) {
            if (!modelFileCtl.model)
                return;
            $scope.$apply(function () {
                modelFileCtl.IsWorking = true;
            });
            fileItem.formData.push({id: modelFileCtl.model.Id});
            if (fileItem.file.name.indexOf('.obj') != -1) {
                var zip = new JSZip();
                zip.file(fileItem.file.name, fileItem._file);
                zip.generateAsync({type: 'blob', compression: 'DEFLATE'})
                    .then(function (blob) {
                        fileItem._file = blob;
                        uploader.uploadItem(fileItem);
                    });
            }
            else {
                uploader.uploadItem(fileItem);
            }
        };

        uploader.onCompleteItem = function (fileItem, response, status, headers) {
            console.log(fileItem._file.name, response);
        };

        uploader.onCompleteAll = function () {
            $scope.$apply(function () {
                var undone = uploader.queue.filter(function(fileItem) { return !fileItem.isUploaded; });
                if (undone.length == 0) {
                    modelFileCtl.IsWorking = false;
                }
                modelFileCtl.Refresh();
            });
        };

        function UploadBlob(fileName, blob) {
            console.log('Uploading Blob ' + fileName);
            var fd = new FormData();
            fd.append('id', modelFileCtl.SelectedItem.Id);
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

        $scope.$on('selectModel', function (event, model) {
            modelFileCtl.model = model;
            modelFileCtl.Refresh();
        });

        modelFileCtl.Refresh();
    }])
    .controller('newModelController', ['$scope', '$http', 'FileUploader', function ($scope, $http, FileUploader) {
        var ctl = this;

        ctl.IsActive = false;
        ctl.Step = 0;
        ctl.IsWorking = false;

        var uploader = $scope.uploader = new FileUploader({
            url: 'upload.php'
        });

        $scope.$on('newModel', function () {
            ctl.IsActive = true;
            ctl.Step1 = true;
            ctl.Step2 = false;
            ctl.Step3 = false;
            ctl.IsWorking = false;
            ctl.Refresh();
        });

        ctl.Refresh = function () {

        };

        ctl.PrevStep = function () {
            if (ctl.Step2) {
                ctl.Step1 = true;
                ctl.Step2 = false;
                ctl.Step3 = false;
            }
            else if (ctl.Step3) {
                ctl.Step1 = false;
                ctl.Step2 = true;
                ctl.Step3 = false;
            }
        };

        ctl.NextStep = function () {
            if (ctl.Step1) {
                ctl.Step1 = false;
                ctl.Step2 = true;
                ctl.Step3 = false;
            }
            else if (ctl.Step2) {
                ctl.Step1 = false;
                ctl.Step2 = false;
                ctl.Step3 = true;
            }
        }
    }]);