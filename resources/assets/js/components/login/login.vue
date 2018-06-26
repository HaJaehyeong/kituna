<template>
  <div class="row">
      <!-- <div class="col-md-6 col-md-offset-3">
          <div class="panel panel-default">
              <div class="panel-body">
                <img name="mainLogo" src="/images/logo.png">
                  <div class="form-group">
                      <input
                        v-model="id"
                        class="form-control"
                        type="id"
                        placeholder="id">
                  </div>
                  <div class="form-group">
                     <input
                        v-model="password"
                        class="form-control"
                        type="password"
                        placeholder="password"> 
                  </div>
                  <router-link to="/mainPage" @click="test" class="btn btn-success  pull-right">                  
                    로그인
                  </router-link>
                  <router-link to="/register" class="btn btn-success pull-right">                  
                    회원가입
                  </router-link>
              </div>    
          </div>
      </div> -->


<div id="mainBackgroundDiv">
    <div id="loginDiv" >
        <div class="partInfo" >
            <div class="form-group">
                <input
                    v-model="id"
                    class="form-control"
                    type="id"
                    placeholder="id">
            </div>
            <div class="form-group">
                <input
                    v-model="password"
                    class="form-control"
                    type="password"
                    placeholder="password"> 
            </div>
            <router-link to="/mainPage" @click="test" class="btn btn-success  pull-right">                  
                로그인
            </router-link>
            <router-link to="/register" class="btn btn-success pull-right">                  
                회원가입
            </router-link>
        </div>  
    </div>
</div>


</div>



</template>

<script>
export default { 
    data: () => ({ /*자체 리턴값 나오게 하는법 !!!!*/ 
            id:'',
            password:''
        }),
    methods :{
        test(){
            }
        }
    }

</script>

<style>
#mainBackgroundDiv {
  /* The image used */
  background-image: url("/images/mainPageImage/backgroundVending.png");

  /* Set a specific height */
  height: 100vh;
  width: 100%;

  /* Create the parallax scrolling effect */
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
#loginDiv {
  width: 30%;
  height: 30%;
  margin-left: 35%;
  margin-top: 15%;
  padding: 1%;
  border: 2px solid #24A6BD;
  border-radius: 10px 10px 10px 10px;
  background-color: rgba(88, 88, 88, 0.4);
  overflow-x:hidden;
  overflow-y:hidden;
}

</style>