import { configureStore } from '@reduxjs/toolkit'
import candidateReducer from './modules/candidate'
import candidateStepRedux from './modules/candidate_step'
import stepperSlice from './modules/stepper'
import { persistStore, persistReducer } from 'redux-persist';
import storage from 'redux-persist/lib/storage'; // defaults to localStorage for web

const persistConfig = {
  key: 'root',
  storage,
};

const persistedReducer = persistReducer(persistConfig, candidateReducer);
const persistedStepReducer = persistReducer(persistConfig, stepperSlice);
export const store = configureStore({
  reducer: { candidate: persistedReducer, candidate_step: candidateStepRedux,stepper:persistedStepReducer },
})
export const persistor = persistStore(store);