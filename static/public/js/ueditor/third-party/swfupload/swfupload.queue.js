/*! depei 2013-07-11 */
var SWFUpload;"function"==typeof SWFUpload&&(SWFUpload.queue={},SWFUpload.prototype.initSettings=function(oldInitSettings){return function(userSettings){"function"==typeof oldInitSettings&&oldInitSettings.call(this,userSettings),this.queueSettings={},this.queueSettings.queue_cancelled_flag=!1,this.queueSettings.queue_upload_count=0,this.queueSettings.user_upload_complete_handler=this.settings.upload_complete_handler,this.queueSettings.user_upload_start_handler=this.settings.upload_start_handler,this.settings.upload_complete_handler=SWFUpload.queue.uploadCompleteHandler,this.settings.upload_start_handler=SWFUpload.queue.uploadStartHandler,this.settings.queue_complete_handler=userSettings.queue_complete_handler||null}}(SWFUpload.prototype.initSettings),SWFUpload.prototype.startUpload=function(fileID){this.queueSettings.queue_cancelled_flag=!1,this.callFlash("StartUpload",[fileID])},SWFUpload.prototype.cancelQueue=function(){this.queueSettings.queue_cancelled_flag=!0,this.stopUpload();for(var stats=this.getStats();stats.files_queued>0;)this.cancelUpload(),stats=this.getStats()},SWFUpload.queue.uploadStartHandler=function(file){var returnValue;return"function"==typeof this.queueSettings.user_upload_start_handler&&(returnValue=this.queueSettings.user_upload_start_handler.call(this,file)),returnValue=returnValue===!1?!1:!0,this.queueSettings.queue_cancelled_flag=!returnValue,returnValue},SWFUpload.queue.uploadCompleteHandler=function(file){var continueUpload,user_upload_complete_handler=this.queueSettings.user_upload_complete_handler;if(file.filestatus===SWFUpload.FILE_STATUS.COMPLETE&&this.queueSettings.queue_upload_count++,continueUpload="function"==typeof user_upload_complete_handler?user_upload_complete_handler.call(this,file)===!1?!1:!0:file.filestatus===SWFUpload.FILE_STATUS.QUEUED?!1:!0){var stats=this.getStats();stats.files_queued>0&&this.queueSettings.queue_cancelled_flag===!1?this.startUpload():this.queueSettings.queue_cancelled_flag===!1?(this.queueEvent("queue_complete_handler",[this.queueSettings.queue_upload_count]),this.queueSettings.queue_upload_count=0):(this.queueSettings.queue_cancelled_flag=!1,this.queueSettings.queue_upload_count=0)}});