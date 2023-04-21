// Script to handle tu uppy upload form

import { Uppy, Dashboard, Tus } from "./uppy-3.7.0.min.js"

/**
 * Ajax request of form data to start the upload
 *
 * @param  {Integer} formID    The id of the form element
 * @param  {String}  uuid	     The id of the uploaded element
 *
 * @returns  {String}  Response
 */
function uploadAjax(formID, destination, uuid) {

  // catch form data
  let formData = new FormData(document.getElementById(formID));
  formData.append('uuid', uuid);
  //formData.append('task', 'download');

  let parameters = {
    method: 'POST',
    mode: 'same-origin',
    cache: 'default',
    redirect: 'follow',
    referrerPolicy: 'no-referrer-when-downgrade',
    body: formData,
  };

  async function postData(url, parameters) {
    let response = await fetch(url, parameters);
    if (!response.ok) {
        // on network error
        return "Network-Error: " + response.status + ", " + response.statusText;
    }
    else
    {
      // on success
      return await response.json();
    }
  }

  postData(destination, parameters).then(res => {
      // what to do after fetching
      return res;
  });
}

/**
 * Read out the uuid from the upload URL
 *
 * @param  {String}   uploadURL	   The URL
 *
 * @returns  {void}
 */
function getUuid(uploadURL) {
  let query = uploadURL.split('?')[1];
  let queryArray = query.split('&');

  for (let i = 0; i < queryArray.length; ++i) {
    if(queryArray[i].includes('uuid')) {
      return queryArray[i].replace('uuid=','');
    }
  }

  return '';
}

/**
 * Create the HTML string for a bootstrap modal
 *
 * @param  {Integer}   id	         The id of the modal
 * @param  {String}    content	   Modal body content
 *
 * @returns  {String}   The html string of the popup
 */
function createPopup(id, content) {
  let html =    '<div class="joomla-modal modal fade" id="modal'+id+'" tabindex="-1" aria-labelledby="modal'+id+'Label" aria-hidden="true">';
  html = html +   '<div class="modal-dialog modal-lg">';
  html = html +      '<div class="modal-content">';
  html = html +           '<div class="modal-header">';
  html = html +               '<h3 class="modal-title" id="modal'+id+'Label">'+Joomla.JText._("COM_JOOMGALLERY_DEBUG_INFORMATION")+'</h3>';
  html = html +               '<button type="button" class="btn-close novalidate" data-bs-dismiss="modal" aria-label="'+Joomla.JText._("JCLOSE")+'"></button>';
  html = html +           '</div>';
  html = html +           '<div class="modal-body">';
  html = html +               '<div id="'+id+'-ModalBody">'+content+'</div>';
  html = html +           '</div>';
  html = html +           '<div class="modal-footer">';
  html = html +               '<button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="event.preventDefault()" aria-label="'+Joomla.JText._("JCLOSE")+'">'+Joomla.JText._("JCLOSE")+'</button>';
  html = html +           '</div>';
  html = html +      '</div>';
  html = html +   '</div>';
  html = html + '</div>';

  return html;
}

/**
 * Add button to uppy upload form
 *
 * @param  {Object}   file	  The Uppy file that was uploaded.
 * @param  {String}   type	  Button type. success or danger
 */
function createBtn(file, type) {
  // Create button
  let btn = document.createElement('div');
  btn.innerHTML = '<button type="button" class="btn btn-'+type+' btn-sm" data-bs-toggle="modal" data-bs-target="#modal'+file.uuid+'">'+Joomla.JText._("COM_JOOMGALLERY_DEBUG_INFORMATION")+'</button>';
  btn.classList.add('uppy-Dashboard-Item-debug-msg');
  btn.classList.add('success');

  // Add button to form
  document.getElementById('uppy_'+file.id).lastChild.firstChild.appendChild(btn);
}

/**
 * Set a synchronous pause.
 * 
 * @param  {Integer}    Time to wait in milliseconds
 */
function wait(ms) {
  let start = Date.now();
  let now = start;
  while (now - start < ms) {
    now = Date.now();
  }
}

/**
 * Set an error in a specific uppy file
 * 
 * @param  {String}           error      Error message
 * @param  {object}           uppy       The uppy object
 * @param  {object|Integer}   file       Object or ID of the uppy file
 * @param  {object}           response   Response object
 */
function uppySetFileError(error, uppy, file, response) {
  let errorMsg = error || 'Unknown error';
  let fileID = (typeof file == 'number') ? file : file.id;

  // Add error to global uppy object
  uppy.setState({ error: errorMsg });

  // Add error to specific uppy file
  if (fileID in uppy.getState().files) {
    uppy.setFileState(fileID, {
      error: errorMsg,
      response,
    });
  }
}

var callback = function() {
  // document ready function
  
  let uppy = new Uppy({
    autoProceed: false,
    restrictions: {
      maxFileSize: window.uppyVars.maxFileSize,
      allowedFileTypes: ['image/*', 'video/*', 'audio/*', 'text/*', 'application/*'],
    }
  });

  if(uppy != null)
  {
    document.getElementById('drag-drop-area').innerHTML = '';
  }

  uppy.use(Dashboard, {
    inline: true,
    target: '#drag-drop-area',
    showProgressDetails: true,
    metaFields: [
      { id: 'imgtitle', name: Joomla.JText._("JGLOBAL_TITLE"), placeholder: Joomla.JText._("COM_JOOMGALLERY_FILE_TITLE_HINT")},
      { id: 'imgtext', name: Joomla.JText._("JGLOBAL_DESCRIPTION"), placeholder: Joomla.JText._("COM_JOOMGALLERY_FILE_DESCRIPTION_HINT")},
      { id: 'author', name: Joomla.JText._("JAUTHOR"), placeholder: Joomla.JText._("COM_JOOMGALLERY_FILE_AUTHOR_HINT")}
    ],
  });

  uppy.use(Tus, {
    endpoint: window.uppyVars.TUSlocation,
    retryDelays: [0, 1000, 3000, 5000],
    allowedMetaFields: null,
  });

  uppy.on('upload', (data) => {
    // data object consists of `id` with upload ID and `fileIDs` array
    // with file IDs in current upload
    // data: { id, fileIDs }
    console.log('Starting upload '+data.id+' for files '+data.fileIDs);
    console.log(data);
});

  uppy.on('upload-success', (file, response) => {
    // single uppy upload was successful
    console.log('Upload of '+file.name+' successful.');

    // custom function
    wait(5000);

    // Set error if custom function fails
    uppySetFileError('Image manipulation failed...', uppy, file.id, response);

    // Resolve uuid
    file.uuid = getUuid(response.uploadURL);

    // Add Button to upload form
    createBtn(file, 'success');

    // Add Popup
    let div = document.createElement('div');
    div.innerHTML = createPopup(file.uuid, 'Upload of file "'+file.name+'" using Uppy successful.<br />Upload-ID: '+file.uuid+'<br />Debug-Info will be added here...');
    document.getElementById('popup-area').appendChild(div);

    new bootstrap.Modal(document.getElementById('modal'+file.uuid));
  });

  uppy.on('upload-error', (file, error, response) => {
    // single uppy upload failed
    console.log('Upload of '+file.name+' failed.');
    console.log(response);
    console.log(file);
    console.log(uppy.getState());

    // Resolve uuid
    file.uuid = getUuid(response.uploadURL);

    // Add Button to upload form
    createBtn(file, 'danger');

    // Add Popup
    let div = document.createElement('div');
    div.innerHTML = createPopup(file.uuid, 'Upload not successful.<br />Debug-Info flow. To be added...');
    document.getElementById('popup-area').appendChild(div);

    new bootstrap.Modal(document.getElementById('modal'+file.uuid));
  });

  uppy.on('complete', (result) => {
    // complete uppy upload was successful
    console.log('Complete upload successfully finished.');
    console.log(result);

    // Add message for successful images
    for (let index = 0; index < result.successful.length; ++index) {
      let res = result.failed[index];
      //createBtn(res, 'success');
    }

    // Add message for failed images
    for (let index = 0; index < result.failed.length; ++index) {
      let res = result.failed[index];
      //createBtn(res, 'danger');
    }
  });

  uppy.on('error', (error) => {
    // complete uppy upload failed
    console.log('Complete upload failed.');
    console.log(error);
  });

}; //end callback

if(document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll))
{
  callback();
} else {
  document.addEventListener("DOMContentLoaded", callback);
}
