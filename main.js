let form = document.forms;

// let temp = [];

let zip = new JSZip();

let predictCount;
let allPredict;

form.form1.formData.addEventListener( 'change', function(e) {

    allPredict=e.target.files.length;

    // console.log(e.target.value.substr(12));

    /*この方法だと画像のbase64データが文字化けしてしまった
    //ファイル総数
    let fileCount = e.target.files.length;
    //formのvalue?を格納
    let result = e.target.files;

    for(let i=0; i<fileCount; i++) {//複数のファイルを処理
        let reader = new FileReader();
        // let result = e.target.files[i];
        //readAsDataURL メソッドは、指定された Blob ないし File オブジェクトを読み込むために使用します。
        // 読込処理が終了すると readyState は DONE に変わり、 loadend イベントが生じます。
        //=>読み込みが終わるとloadイベントが発生するのでaddEventListenerでキャッチして同期処理
        reader.readAsText( result[i] );
        // reader.addEventListener('load', function(e) {//onloadでも記述可能
        //
        //     console.log(e.target);
        //
        // })
        reader.onload = function () {
            console.log( this.result );
        }
    }

     */


    for (let i = 0; i < this.files.length; i++) {
        let fileReader = new FileReader() ;
        let file = this.files[i] ;
        fileReader.readAsDataURL( file ) ;
        fileReader.onload = function () {
            // temp.push(this.result);####この表記だとpushの処理に時間がかかるのかrequestでのimageBytesに値が間に合わない
            // console.log(e.target.files[i]) ;
            // console.log(temp[i].split( ',' )[1]);
            //ファイル分別


            let request = {
                payload:{
                    image:{//リクエスト時　data:image/jpeg;base64,　の部分をカットする必要がある
                        imageBytes:String(this.result).split( ',' )[1]
                        // imageBytes:temp[i].substr( 23 )
                        // imageBytes:temp[i]
                    }
                }
            };

            let XHR = new XMLHttpRequest();
            XHR.open("POST", url, true);
            XHR.setRequestHeader('Authorization',`Bearer ${token}`);
            XHR.setRequestHeader('Content-Type','application/json');
            XHR.send(JSON.stringify(request));
            // XHR.send(JSON.stringify({
            //     payload:{
            //         image:{//リクエスト時　data:image/jpeg;base64,　の部分をカットする必要がある
            //             imageBytes:String(temp[i]).split( ',' )[1]
            //             // imageBytes:temp[i].substr( 23 )
            //             // imageBytes:temp[i]
            //         }
            //     }
            // }));

            XHR.onreadystatechange=function(){
                if(XHR.readyState==4 && XHR.status==200){
                    // console.log(JSON.parse(XHR.responseText).payload[0].displayName);
                    console.log(XHR.responseText);
                    //ファイル+iの部分は取得したラベル名
                    // zip.folder(JSON.parse(XHR.responseText).payload[0].displayName).file(String(e.target.value.substr(12)), file);
                    zip.file(JSON.parse(XHR.responseText).payload[0].displayName+"/"+e.target.files[i].name, file);
                    // console.log(XHR.responseText);
                    // console.log(String(e.target.value.substr(12)));
                }
            };



        };
    }
});


/*テンプレート
var zip = new JSZip();

// create a file and a folder
zip.file("nested/hello.txt", "Hello World\n");
// same as
// zip.folder("nested").file("hello1.txt", "Hello World\n");
// zip.folder("nested").file("hello2.txt", "Hello World\n");


zip.generateAsync({type:"blob"}).then(function(content) {
    // see FileSaver.js
    saveAs(content, "example.zip");
});
*/

let url = "https://automl.googleapis.com/v1beta1/projects/fine-climber-240416/locations/us-central1/models/ICN8753464127353970294:predict";

let token = "ya29.c.EloOB7iGA4E1vXTiWW_pLencoD850mpvNQvVowGmcB1v0ENRkrlmQOwLXt_WCIH3vHbMpeuqM1d_An6e-0GBBaQrFQUuqRikYjKKaZvel09xix8ti9T8DRU4JnI";

// function send() {//うごいたやつ
//     let request = {
//         payload:{
//             image:{//リクエスト時　data:image/jpeg;base64,　の部分をカットする必要がある
//                 imageBytes:temp[0].substr( 23 )
//             }
//         }
//     };
//
//     console.log(temp[0]);
//     //
//     let XHR = new XMLHttpRequest();
//     XHR.open("POST", url, true);
//     XHR.setRequestHeader('Authorization',`Bearer ${token}`);
//     XHR.setRequestHeader('Content-Type','application/json');
//     XHR.send(JSON.stringify(request));
// }

function send() {
    zip.generateAsync({type:"blob"}).then(function(content) {
        // see FileSaver.js
        saveAs(content, "results.zip");
    });
}


