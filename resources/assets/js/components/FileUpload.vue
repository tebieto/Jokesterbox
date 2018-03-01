<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">File Component</div>

                    <div class="panel-body">
                  
					 <legend>Form Title</Legend>
					 <div class="form-group">
					 <label>Upload Files</label>
					 <input type="file" class="form-control" name="" id="" placeholder="input..." @change="fieldChange"/>
					 </div>
					 <button type="submit" class="btn btn-primary" @click="uploadFile">submit</button>
					
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
		data(){
		return {
		attachment: null,
		form: new FormData
		}
		},
		methods: {
		fieldChange(e){
		let selectedFile=e.target.files[0];
		this.attachment=selectedFile;
		},
		uploadFile(){
		this.form.append('pic', this.attachment);
		const config = {headers: {'Content-Type': 'multipart/form-data'}};
		
		axios.post('/upload', this.form, config).then(response=>{
		//success
		console.log(response);
		})
				.catch(response=>{
				//errors
				});
			}
		},
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
