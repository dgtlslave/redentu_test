
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card mt-5">
                    <div class="card-header">
                         Products uploading
                    </div>
                    <div class="card-body">
                        <form enctype="multipart/form-data" method="post">
                            <div class="form-group">
                                <label for="upload">Choose your file:</label>
                                <input type="file" class="form-control-file" id="upload" name="import_file" ref="fileUpload"  @change="onFileChange"  >
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success form-control" @click.prevent="proceedAction">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import axios from 'axios'

export default {
     data() {
        return {
          error: {},
          import_file: '',
        }
      },
      methods: {
        onFileChange(e) {
            this.import_file = e.target.files[0];
        },

        proceedAction() {
            if(this.import_file) {

                let formData = new FormData();
                formData.append('import_file', this.import_file);

                axios.post('/import', formData, {headers: { 'content-type': 'multipart/form-data' }}).then(response => {
                    console.log(response.data)
                        if(response.data.message == 'ok') {
                            let not_saved = response.data.count - response.data.saved
                            Swal.fire(
                                'Uploaded successfully!',
                                'Was readed: ' + response.data.count + ' row/s. Saved unique row/s: ' + response.data.saved + '. Don`t save duplicated row/s: ' + not_saved,
                                'success'
                            ).then((result) => {
                                this.clear()
                            })
                        } else if(response.data.message == 'error') {
                            Swal.fire(
                                'Something went wrong!',
                                'Pls, check file extantion.',
                                'error'
                            ).then((result) => {
                                this.clear()
                            })
                        } else if(response.data.message == 'break') {
                            Swal.fire(
                                'Array integrity is broken!',
                                'Error detected in ' + response.data.empty_cell + ' line of your file. Pls fix & try again.',
                                'warning'
                            ).then((result) => {
                                this.clear()
                            })
                        }
                    })
                    .catch(error => {
                        // code here when an upload is not valid
                        this.uploading = false
                        this.error = error.response.data
                        console.log('check error: ', this.error)
                    });

            } else {
                Swal.fire(
                    'The file?',
                    'You forget choose your file!',
                    'question'
                ).then((result) => {
                    this.clear()
                })
            }
            },
        clear () {
            this.$refs.fileUpload.value = '';
            this.import_file = '';
        },
    }
  }

</script>
