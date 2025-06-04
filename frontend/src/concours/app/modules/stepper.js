// src/features/stepper/stepperSlice.js
import { createSlice } from '@reduxjs/toolkit';

const initialState = {
  step: 1,
  totalSteps: 5,
};

const stepperSlice = createSlice({
  name: 'stepper',
  initialState,
  reducers: {
    nextStep: (state) => {
      if (state.step < state.totalSteps) {
        state.step += 1;
      }
    },
    prevStep: (state) => {
      if (state.step > 1) {
        state.step -= 1;
      }
    },
    setStep: (state, action) => {
      state.step = action.payload;
    },
    initStep(state){
      state.step = 1
    }
  },
});

export const { nextStep, prevStep, setStep,initStep } = stepperSlice.actions;

export default stepperSlice.reducer;
