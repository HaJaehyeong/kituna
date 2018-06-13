<template>
  <div class="row">
      <div class="col-md-6 col-md-offset-3">
          <div class="panel panel-default">
              <div class="panel-body">
                  <div class="form-group">
                    아이디 : 
                      <input
                      v-model="id"
                      class="form-control"
                      type="text"
                      placeholder="id">
                  </div>
                  <div class="form-group">
                    비밀번호 :
                     <input
                      v-model="password"
                      class="form-control"
                      type="password"
                      placeholder="password"> 
                  </div>
                  <div class="form-group">
                    비밀번호 확인 :
                      <input
                      v-model="passwordConfirm"
                      class="form-control"
                      type="password"
                      placeholder="password">
                  </div>
                  <router-link to="/" class="btn btn-success  pull-right">
                        취소
                  </router-link>
                  <router-link to="/" class="btn btn-success  pull-right">
                        완료
                  </router-link>
              </div>    
          </div>
      </div>
     
  </div>
</template>

<script>
export default {
    data(){
        return{
            id : '',
            password :'',
            passwordConfirm : ''
        }
    }
}
</script>

<style>

</style>