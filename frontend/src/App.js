import { BrowserRouter, Route, Routes } from "react-router-dom";
import { PersistGate } from 'redux-persist/integration/react';
import { ToastContainer } from "react-toastify";
import "react-toastify/ReactToastify.css";

// Pages
import Home from "./pages/Home";
import LoginPage from "./pages/Login";
import Candidate from "./pages/Candidate";
import AfficheDonnee from "./pages/AfficheDonnee";
import SiteExam from "./pages/SiteExam";
import AncienneEpreuvePage from "./components/stepItem/AncienneEpreuve";
import Page500 from "./pages/errors/Page500";
import Page404 from "./pages/errors/Page404";
import PwdRecover from "./components/stepItem/PwdRecover"

// Components
import Loading from "./components/stepModal/Loading";
import PrivateRoute from "./components/PrivateRoute";
import ClientLayout from "./components/layouts/ClientLayout";
import BaseLayout from "./components/layouts/BaseLayout";
import Dashboard from "./components/screens/dashboard/DashboardScreen";

// Store
import { persistor } from "./app/store";
import SuccessPage from "./pages/SuccessPage";
import Compte from "./pages/admin/pages/Compte";
import AdminCandidate from "./pages/admin/pages/AdminCandidate";

function App() {
  return (
    <PersistGate loading={<Loading />} persistor={persistor}>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<ClientLayout />}>
            <Route index element={<Home />} />
            <Route path="login" element={<LoginPage />} />
            <Route path="site-exam" element={<SiteExam />} errorElement={<Page500 />} />
            <Route path="candidate" element={<PrivateRoute />}>
              <Route index element={<Candidate />} errorElement={<Page500 />} />
            </Route>
            <Route path="affiche-data" element={<PrivateRoute />}>
              <Route index element={<AfficheDonnee />} errorElement={<Page500 />} />
            </Route>
            <Route path="success" element={<PrivateRoute />}>
              <Route index element={<SuccessPage />} errorElement={<Page500 />} />
            </Route>
            <Route path="/ancienne-epreuve" element={<AncienneEpreuvePage />} />
            <Route path="/pwd-recover" element={<PwdRecover />} />
            <Route path="*" element={<Page404 />} />
          </Route>

          <Route path="/admin" element={<BaseLayout />}>
            <Route path="" element={<Dashboard />} errorElement={<Page500 />} />
            <Route path="login" element={<LoginPage />} />
            <Route path="candidates" element={<AdminCandidate/>} />
            <Route path="comptes" element={<Compte />} />
            <Route path="*" element={<Page404 />} />
          </Route>
        </Routes>

        <ToastContainer />
      </BrowserRouter>
    </PersistGate>
  );
}

export default App;
