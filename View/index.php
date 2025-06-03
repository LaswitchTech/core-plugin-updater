<!--
  Core Framework - View File

  @license    MIT (https://mit-license.org/)
  @author     Full Name <user@domain.com>
-->
<?php if($this->Auth->isAuthorized('Administrator',1)): ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title><?= $this->Locale->get($this->label()) ?></title>
            <script src="/js/jquery/js/jquery.min.js"></script>
            <?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'style.php'; ?>
        </head>
        <body>
            <div class="d-flex justify-content-center align-items-center vh-100 vw-100" style="overflow: auto;">
                <div style="padding: 2rem;">
                    <h1 class="w-100 text-center"><?= $this->label() ?></h1>
                    <div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner spinner-100 info">
                                    <i class="question info"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Are you ready?') ?></h4>
                                <pre style="margin-top: 12px; margin-bottom: 0px;"><?= $this->Locale->get('You are about to start updating this application. Make sure to leave the browser open during this process.') ?></pre>
                                <div class="d-flex flex-row align-items-center" style="margin-top: 12px;">
                                    <button type="button" data-action="begin"><?= $this->Locale->get('Start Updating') ?></button>
                                </div>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner spinner-100 success">
                                    <i class="check success"></i>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Inititializing') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Enable maintenance mode') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner spinner-25"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Check for write permission') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Backup the current instance') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Download the new release') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Verify integrity') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Extract the new release') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Copy new files') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Update the dependencies') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Upgrade the database') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Replace entry points') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Clean up') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                        <div class="step d-flex flex-row align-items-center d-none opacity-0" style="margin-top: 24px;">
                            <div class="d-flex-shrink" style="padding-right: 24px;">
                                <div class="spinner"></div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="m-0"><?= $this->Locale->get('Disable maintenance mode') ?></h4>
                                <pre class="m-0"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>

        <script>

            // Set CSRF Token
            var CSRF_KEY = "<?= $this->CSRF->key() ?>";
            var CSRF_TOKEN = "<?= $this->CSRF->token() ?>";

            // Wait for document to load
            $(document).ready(function(){

                // Initialize
                $('.step').removeClass('opacity-100').addClass('d-none opacity-0');
                $('.spinner').each(function(){
                    // Check if the spinner has a question icon
                    if($(this).find('i.question').length <= 0){
                        $(this).removeClass('spinner-100 spinner-75 spinner-50 spinner-25');
                        $(this).removeClass('success info warning danger');
                        $(this).html('');
                    }
                });

                // Show a step
                function showStep(element, callback = null){
                    element.removeClass('d-none');
                    setTimeout(() => {
                        element.removeClass('opacity-0').addClass('opacity-100');
                        if(typeof callback === 'function'){
                            callback(element);
                        }
                    }, 0);
                }

                // Hide a step
                function hideStep(element, callback = null){
                    element.removeClass('opacity-100').addClass('opacity-0');
                    setTimeout(() => {
                        element.addClass('d-none');
                        if(typeof callback === 'function'){
                            callback(element);
                        }
                    }, 300);
                }

                // Open the first step
                showStep($('.step').first());

                // Begin the update
                $('[data-action="begin"]').on('click', function(){

                    // Set the current step
                    var current = $(this).closest('.step');

                    // Create the metadata object
                    var metadata = {};

                    // Step 0: Start the update
                    function step0(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Hide the current step
                                hideStep(current, function(element){

                                    // Resolve the promise
                                    resolve(current);
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 1: Initializing
                    function step1(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/fetch',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the metadata object
                                            metadata["maintenance"] = response.maintenance;
                                            metadata["current"] = response.current;
                                            metadata["latest"] = response.latest;
                                            metadata["url"] = response.url;
                                            metadata["checksum"] = response.checksum;
                                            metadata["available"] = response.available;

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Check if an update is available
                                            if(response.available){

                                                // Update the spinner
                                                element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                                // Resolve the promise
                                                resolve();
                                            } else {

                                                // Reject the promise
                                                reject(response.message);
                                            }
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 2: Enable maintenance mode
                    function step2(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/maintenance/on',
                                        type: 'GET',dataType: 'json',
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the metadata object
                                            metadata["maintenance"] = response.status;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Check if an update is available
                                            if(response.status){

                                                // Update the spinner
                                                element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                                // Resolve the promise
                                                resolve();
                                            } else {

                                                // Reject the promise
                                                reject(response.message);
                                            }
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 3: Check for write permission
                    function step3(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/writable',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the metadata object
                                            metadata["writable"] = response.writable;

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Check if an update is available
                                            if(response.writable){

                                                // Update the spinner
                                                element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                                // Resolve the promise
                                                resolve();
                                            } else {

                                                // Reject the promise
                                                reject(response.message);
                                            }
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 4: Backup the current instance
                    function step4(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/backups/init',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the metadata object
                                            metadata["backup"] = {
                                                "path": response.path,
                                                "file": response.file,
                                                "uuid": response.uuid,
                                            };

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Update the spinner
                                            element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                            // Resolve the promise
                                            resolve();
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 5: Download the new release
                    function step5(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;
                                    data["url"] = metadata["url"];
                                    data["checksum"] = metadata["checksum"];
                                    data["version"] = metadata["latest"];

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/download',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the metadata object
                                            metadata["download"] = {
                                                "archive": response.archive,
                                                "checksum": response.checksum,
                                            };

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Update the spinner
                                            element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                            // Resolve the promise
                                            resolve();
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 6: Verify integrity
                    function step6(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;
                                    data["version"] = metadata["latest"];

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/validate',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the metadata object
                                            metadata.download.checksum["valid"] = response.valid;
                                            metadata.download.checksum["checksum"] = response.checksum;

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Update the spinner
                                            element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                            // Resolve the promise
                                            resolve();
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 7: Extract the new release
                    function step7(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;
                                    data["version"] = metadata["latest"];

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/extract',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the metadata object
                                            metadata["package"] = {
                                                "path": response.path,
                                                "archive": response.archive,
                                            };

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Update the spinner
                                            element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                            // Resolve the promise
                                            resolve();
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 8: Copy new files
                    function step8(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;
                                    data["version"] = metadata["latest"];

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/copy',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Update the spinner
                                            element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                            // Resolve the promise
                                            resolve();
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 9: Update the dependencies
                    function step9(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/dependencies',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Update the spinner
                                            element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                            // Resolve the promise
                                            resolve();
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 10: Upgrade the database
                    function step10(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;
                                    data["version"] = metadata.current;
                                    data["uuid"] = metadata.backup.uuid;

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/upgrade',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Update the spinner
                                            element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                            // Resolve the promise
                                            resolve();
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 11: Replace the entry points
                    function step11(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/replace',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Update the spinner
                                            element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                            // Resolve the promise
                                            resolve();
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 12: Clean up
                    function step12(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Create the data object
                                    var data = {};
                                    data[CSRF_KEY] = CSRF_TOKEN;
                                    data["uuid"] = metadata.backup.uuid;

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/updater/cleanup',
                                        type: 'POST',dataType: 'json',
                                        data: data,
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the CSRF
                                            CSRF_KEY = response.CSRF.key;
                                            CSRF_TOKEN = response.CSRF.token;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Update the spinner
                                            element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                            // Resolve the promise
                                            resolve();
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Step 13: Disable maintenance mode
                    function step13(){

                        // Create a promise
                        return new Promise((resolve, reject) => {

                            // Try & Catch
                            try {

                                // Show the current step
                                showStep(current.next('.step'),function(element){

                                    // Set the current step
                                    current = element;

                                    // Start the spinner
                                    element.find('.spinner').addClass('spinner-25');

                                    // Ajax Request
                                    $.ajax({
                                        url: '/endpoint.php/maintenance/off',
                                        type: 'GET',dataType: 'json',
                                        error: function(xhr, status, error){

                                            // Reject the promise
                                            reject(xhr.responseText);
                                        },
                                        success: function(response) {

                                            // Update the metadata object
                                            metadata["maintenance"] = response.status;

                                            // Show the message
                                            element.find('pre').text(response.message);

                                            // Check if an update is available
                                            if(!response.status){

                                                // Update the spinner
                                                element.find('.spinner').removeClass('spinner-25').addClass('spinner-100 success').html('<i class="check success"></i>');

                                                // Resolve the promise
                                                resolve();
                                            } else {

                                                // Reject the promise
                                                reject(response.message);
                                            }
                                        }
                                    });
                                });
                            } catch (error) {

                                // Reject the promise
                                reject(error);
                            }
                        });
                    }

                    // Execute the promises sequentially
                    (async function run() {
                        try {

                            // Execute each steps sequentially
                            await step0();
                            await step1();
                            await step2();
                            await step3();
                            await step4();
                            await step5();
                            await step6();
                            await step7();
                            await step8();
                            await step9();
                            await step10();
                            await step11();
                            await step12();
                            await step13();

                            // At this point, all awaited promises above have resolved (no errors).// Add a button to return to the home page
                            let element = $(document.createElement('div')).attr({
                                "class": "d-flex flex-row align-items-center",
                                "style": "margin-top: 12px;"
                            }).appendTo(current.find('.flex-grow'));
                            element.link = $(document.createElement('button')).attr({
                                "type": "button",
                            }).text("<?= $this->Locale->get('Return Home') ?>").appendTo(element);

                            // Add a click event to the button
                            element.link.on('click', function(){

                                // Check if maintenance mode is enabled
                                if(typeof metadata["maintenance"] !== "undefined" && metadata["maintenance"]){

                                    // Disable maintenance mode
                                    $.ajax({
                                        url: '/endpoint.php/maintenance/off',
                                        type: 'GET',dataType: 'json',
                                        success: function(response) {

                                            // Redirect to the home page
                                            window.location.href = '/';
                                        }
                                    });
                                } else {

                                    // Redirect to the home page
                                    window.location.href = '/';
                                }
                            });
                        } catch (err) {

                            // Show the error message
                            current.find('pre').text("<?= $this->Locale->get('An error occurred') ?>: " + err);

                            // Update the spinner
                            current.find('.spinner').removeClass('spinner-25').addClass('spinner-100 danger').html('<i class="x danger"></i>');

                            // Add a button to return to the home page
                            let element = $(document.createElement('div')).attr({
                                "class": "d-flex flex-row align-items-center",
                                "style": "margin-top: 12px;"
                            }).appendTo(current.find('.flex-grow'));
                            element.link = $(document.createElement('button')).attr({
                                "type": "button",
                            }).text("<?= $this->Locale->get('Return Home') ?>").appendTo(element);

                            // Add a click event to the button
                            element.link.on('click', function(){

                                // Check if maintenance mode is enabled
                                if(typeof metadata["maintenance"] !== "undefined" && metadata["maintenance"]){

                                    // Disable maintenance mode
                                    $.ajax({
                                        url: '/endpoint.php/maintenance/off',
                                        type: 'GET',dataType: 'json',
                                        success: function(response) {

                                            // Redirect to the home page
                                            window.location.href = '/';
                                        }
                                    });
                                } else {

                                    // Redirect to the home page
                                    window.location.href = '/';
                                }
                            });
                        }
                    })();
                });
            });
        </script>
    </html>
<?php else: $this->interrupt(); $this->Router->render('403'); endif; ?>
