var EventEmitter = require('events').EventEmitter; 
var event = new EventEmitter(); 
var gulp = require('gulp');
var runSequence = require('gulp-run-sequence');
var git = require('gulp-git');
var g_WatchFlag=true;
var g_WatchSingle=true;
var fileIndex=0;
/*var fileSrc="E:/study_doc/aabbcc/c.txt";*/
/*var fileSrcS=[
   "E:/study_doc/aabbcc/m.txt",
   "E:/study_doc/aabbcc/n.txt"
];*/
//var fileSrcS=[
var fileSrc=[
          // "E:/study_doc/aabbcc/c.txt",
          // "E:/study_doc/aabbcc/d.txt"
           "*",
           "!.git",
           "!*.bak",
            "!temp",
          
          ];
//var fileSrc= fileSrcS[0];
var event = new EventEmitter(); 
gulp.task('init', function(){
  git.init(function (err) {
    if (err) 
    {
      throw err;
    }
    else
    {
        console.log(" push ok ok ok ok ok");
    }
  });
});

gulp.task('add', function(){
   return gulp.src(fileSrc)
    .pipe(git.add());
});

gulp.task('commit', function(){
  return gulp.src(fileSrc)
    .pipe(git.commit('gulp commit'));
});

gulp.task('removeremote', function(){
  git.removeRemote('origin', function (err) {
    if (err) throw err;
  });
});

gulp.task('addremote', function(){
  git.addRemote('origin', 'https://github.com/garyyan/study_doc.git', function (err) {
    if (err) throw err;
  });
});

gulp.task('push', function(){
  git.push('origin', 'master', function (err) {
    if (err) throw err;
  });
});



gulp.task('pull', function(){
  git.pull('origin', 'master', function (err) {
    if (err) throw err;
  });
});

gulp.task('pull_rebase', function(){
  git.pull('origin', 'master', {args: '--rebase'}, function (err) {
    if (err) throw err;
  });
});


gulp.task('checkout', function(){
  git.checkout('master', function (err) {
    if (err) throw err;
  });
});


gulp.task('reset', function(){
  git.reset('origin', function (err) {
    if (err) throw err;
  });
});

//Run git fetch 
// Fetch refs from origin 
gulp.task('fetch', function(){
  git.fetch('origin', '', function (err) {
    if (err) throw err;
  });
});




//============for combine the task================
gulp.task('add_2', function(cb){
  //cb(err);// 如果 err 不是 null 和 undefined，流程会被结束掉，下一任务不会被执行
   return gulp.src(fileSrc)
    .pipe(git.add());
});

// 标注一个依赖，依赖的任务必须在这个任务开始之前被完成
//gulp.task('commit_2',  function(cb){
gulp.task('commit_2', ['add_2'], function(cb){
  // cb(err);// 如果 err 不是 null 和 undefined，流程会被结束掉，下一任务不会被执行
  return gulp.src(fileSrc)
    .pipe(git.commit('gulp commit update 3'));
});

// 标注一个依赖，依赖的任务必须在这个任务开始之前被完成
//gulp.task('push_2', function(){
gulp.task('push_2',['commit_2'], function(cb){
  // cb(err);// 如果 err 不是 null 和 undefined，流程会被结束掉，下一任务不会被执行
  git.push('origin', 'master', function (err) {
    if (err) 
    {
      throw err;
      return
    }
   else
    {
      cb();
    }
  });
});

gulp.task('push2_complete',['push_2'], function(){
  // cb(err);// 如果 err 不是 null 和 undefined，流程会被结束掉，下一任务不会被执行
   console.log("push_complete ok ok ok ok");
   event.emit('one_submit_complete');
});

//gulp.task('default', ['add_2', 'commit_2', 'push_2']);
/*gulp.task('default',function(cb){
   return gulp.src(fileSrc)
    .pipe(git.add())
    .pipe(git.commit('gulp commit'))
    .pipe(git.push('origin', 'master'));
   // return stream;
});*/

function taskDone(fileIndex)
{
  event.emit('one_submit_complete');
 
}
gulp.task('push-one', ['add_2', 'commit_2', 'push_2','push2_complete']);

/*gulp.task('default', function(){
    //var fileIndex=0;
   //for (var fileIndex in fileSrcS)
   fileSrcS.forEach(function(e)
   {
     fileSrc=e;//fileSrcS[fileIndex];
     console.log(fileSrc+" start");
     runSequence(
      'add_2',
      'commit_2',
      'push_2',
      taskDone
      );
     console.log(fileSrc+" end");
  })
});
*/

/*gulp.task('default',['push-one'] ,function(){
    //fileSrcS.forEach(function(e)
       
       //event.emit('one_submit_complete');
       console.log(fileSrc+" start");
   // runSequence('push-one',taskDone);
    console.log(fileSrc+" end");
     
    //);
});*/


gulp.task('push-all', ['add_2', 'commit_2', 'push_2']);
gulp.task('default',['push-all'] ,function(){
    
       console.log(" push all start");
   
     
    //);
});

/*gulp.task('default', function(){
   
    console.log("default task run");
     
    //);
});*/



event.on('one_submit_complete', function() { 

  /* setTimeout(function() { 
   event.emit('wait_complete_delay'); 
   }, 5000); 
*/
   console.log('one_submit_complete 事件触发'); 
    console.log(fileSrc+" task done");
    if(fileIndex+1<fileSrcS.length)
    {
      fileIndex++;
      fileSrc=fileSrcS[fileIndex];
      console.log(fileSrc+" start");
      runSequence('push-one',taskDone);
      console.log(fileSrc+" end");
    }
    else
    {
       console.log("all task complete");
    }
  
 
}); 


event.on('wait_complete_delay', function(){
     console.log('one_submit_complete 事件触发'); 
    console.log(fileSrc+" task done");
    if(fileIndex+1<fileSrcS.length)
    {
      fileIndex++;
      fileSrc=fileSrcS[fileIndex];
      console.log(fileSrc+" start");
      runSequence('push-one',taskDone);
      console.log(fileSrc+" end");
    }
    else
    {
       console.log("all task complete");
    }
});