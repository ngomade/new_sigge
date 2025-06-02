import { createSlice } from '@reduxjs/toolkit'

const initialState = {
  candidate_state: {
    finish:false
  },
  session:{

  }
}

export const candidateSlice = createSlice({
  name: 'candidate',
  initialState,
  reducers: {
    push_candidate_info:(state,action)=>{
        state.candidate_state = {
            ...state.candidate_state,
            ...action.payload
        }
    },
    setCurrentSession(state,{payload}){
      state.session = {
        ...state.session,
        ...payload
      }
    },
    logout(state){
      sessionStorage.removeItem("user");
      sessionStorage.removeItem("token");
      state.candidate_state = {}
    }
  },
})

// Action creators are generated for each case reducer function
export const { push_candidate_info,setCurrentSession,logout } = candidateSlice.actions
//export const { push_candidate_info,setCurrentSession } = candidateSlice.actions

export default candidateSlice.reducer