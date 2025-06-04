import { createSlice } from '@reduxjs/toolkit'

const initialState = {
  steps: {
    step_1:false,
    setp_2:false,
    setp_3:false,
    setp_4:false,
    setp_5:false,
    setp_6:false,
  },
}

export const candidateStepRedux = createSlice({
  name: 'candidate_step',
  initialState,
  reducers: {
   
    set_step_state:(state,action)=>{
        state.steps = {
            ...state.steps,
            ...action.payload
        }
    }
  },
})

// Action creators are generated for each case reducer function
export const { set_step_state } = candidateStepRedux.actions

export default candidateStepRedux.reducer