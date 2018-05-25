import {Page, NavController, Platform} from 'ionic-angular';
import {Http} from 'angular2/http';
import {Camera} from 'ionic-native';
import {NgZone} from 'angular2/core';

//using Camera ionic-native, using navigator.camera for cordova plugin
declare var Camera:any;
declare var navigator:any;

//auto import from installation
declare var FileUploadOptions:any;
declare var FileTransfer:any;

@Page({
  templateUrl: 'build/pages/page1/page1.html',
})

export class Page1 {

    //images
    _zone: NgZone;
    platform:Platform;
    images: Array<{src: String}>;

    //server
    data:any;
    http:any;

    //
    constructor(platform:Platform, _zone:NgZone, http:Http) {
        this._zone = _zone;
        this.platform = platform;
        this.images = [];
        this.data = {}; //obj
        this.data.name = '';
        this.data.phone = '';
        this.data.response = '';
        this.http = http;

        //check obj
        this.platform.ready().then(() => {
            console.log(FileTransfer);
        })
    }

    //must use navigator.camera in var options vs camera
    selectPicture() {
        console.log("entered");

        var options = {
            quality: 50,
            destinationType: navigator.camera.DestinationType.FILE_URI, //1, filepath not base64string
            sourceType: navigator.camera.PictureSourceType.PHOTOLIBRARY, //0
            encodingType: navigator.camera.EncodingType.JPEG,
            targetWidth: 100,
            targetHeight: 100,
            //popoverOptions: CameraPopoverOptions, //for ios not needed on andriod
            saveToPhotoAlbum: false,
            correctOrientation:true
        };

        //using Camera from ionic-native - BUILD TIME 13secs
        Camera.getPicture(options).then((imageURI)=>{
            this.onSuccess(imageURI);
        },(error)=>{
            this.onFail(error);
        });
    }

    //success callback for Camera getpicture, ionic-native
    onSuccess(imageURI) {
        console.log(imageURI);

        //let imagedata = "data:image/jpeg;base64," + imageURI; //only for DATA_URL base64 string
        this._zone.run(()=> this.images.unshift({
            src: imageURI
        }));
        console.log(this.images);
    }

    //fail callback for getpicture
    onFail(message) {
        alert('Failed because: ' + message);
    }

    //using filetransfer plugin to send binary img data because db field is blob, instead of base64 string in json if db field is text
    submit() {
        console.log(this.data.name);
        console.log(this.data.phone);
        console.log(this.images);
        console.log(this.images[0]);
        console.log(this.images[0].src);

        var ft = new FileTransfer();
        var filename = this.images[0].src;
        var options = new FileUploadOptions();
        options.fileKey = "file";
        options.fileName = filename;
        options.mimeType = "image/jpeg";

        options.params = {
            name: this.data.name,
            phone: this.data.phone,
            picture: options.filename
        };

        ft.upload(filename, 'http://ionicapp.codingpandas.org/write.php', this.uploadSuccess, this.uploadFailed, options);
    }

    uploadSuccess = (result: any) : void => {
        console.log("Code = " + result.responseCode.toString()+"\n");
        console.log("Response = " + result.response.toString()+"\n");
        console.log("Sent = " + result.bytesSent.toString()+"\n");
        alert("SUCCESS!!!");
    };
    uploadFailed = (err: any) : void => {
        alert(err);
        console.log(err);
        console.log(err.error);
        alert("An error has occurred: Code = " + err.code);
    };

    //reload page
    reload(){
        location.reload();
    }
}
//END