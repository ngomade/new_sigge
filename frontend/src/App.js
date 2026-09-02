import {BrowserRouter, Route, Routes} from "react-router-dom";
import {PersistGate} from 'redux-persist/integration/react';
import {ToastContainer} from "react-toastify";
import "react-toastify/ReactToastify.css";

// Pages
import Home from "./Pages/Home";
import LoginPage from "./concours/pages/Login";
import Candidate from "./concours/pages/Candidate";
import AfficheDonnee from "./concours/pages/AfficheDonnee";
import SiteExam from "./concours/pages/SiteExam";
import AncienneEpreuvePage from "./concours/components/stepItem/AncienneEpreuve";
import Page500 from "./concours/pages/errors/Page500";
import Page404 from "./concours/pages/errors/Page404";
import PwdRecover from "./concours/components/stepItem/PwdRecover"

// Components
import Loading from "./concours/components/stepModal/Loading";
import PrivateRoute from "./concours/components/PrivateRoute";
import ClientLayout from "./concours/components/layouts/ClientLayout";
import BaseLayout from "./concours/components/layouts/BaseLayout";
import Dashboard from "./concours/components/screens/dashboard/DashboardScreen";

// Store
import {persistor} from "./concours/app/store";
import SuccessPage from "./concours/pages/SuccessPage";
import Compte from "./concours/pages/admin/pages/Compte";
import AdminCandidate from "./concours/pages/admin/pages/AdminCandidate";
import GuestRoute from "./concours/components/GuestRoute";
import Login from "./Pages/Login";
import UnderDevelopment from "./Pages/UnderDevelopment";
import OrganigramPage from "./Pages/OrganigramPage";
import StaffPage from "./Pages/StaffPage";
function App() {
    return (
        <PersistGate loading={<Loading/>} persistor={persistor}>
            <BrowserRouter>
                <Routes>
                     {/* Routes Publiques */}
                    <Route path="/" element={<Home />} />
                    <Route path="/login" element={<Login />} />
                    <Route path="/under-development" element={<UnderDevelopment />} />
                    <Route path="/organigram" element={<OrganigramPage />} />
                    <Route path="/staff" element={<StaffPage />} />





                    {/*Ici ceux sont les routes dediés pour les concours  */}
                    {/* <Route path="/" element={<ClientLayout/>}>
                        <Route index element={<Home/>}/>
                        <Route path="login" element={<GuestRoute/>}>
                            <Route index element={<LoginPage/>}/>
                        </Route>
                        <Route path="site-exam" element={<SiteExam/>} errorElement={<Page500/>}/>
                        <Route path="candidate" element={<Candidate/>} errorElement={<Page500/>}/>
                        <Route path="affiche-data" element={<PrivateRoute/>}>
                            <Route index element={<AfficheDonnee/>} errorElement={<Page500/>}/>
                        </Route>
                        <Route path="success" element={<PrivateRoute/>}>
                            <Route index element={<SuccessPage/>} errorElement={<Page500/>}/>
                        </Route>
                        <Route path="/ancienne-epreuve" element={<AncienneEpreuvePage/>}/>
                        <Route path="/pwd-recover" element={<GuestRoute/>}>
                            <Route index element={<PwdRecover/>}/>
                        </Route>
                        <Route path="/reset-pwd" element={<PwdReset/>} />
                        <Route path="*" element={<Page404/>}/>
                    </Route> *

                    <Route path="/admin" element={<BaseLayout/>}>
                        <Route path="" element={<Dashboard/>} errorElement={<Page500/>}/>
                        <Route path="login" element={<LoginPage/>}/>
                        <Route path="candidates" element={<AdminCandidate/>}/>
                        <Route path="comptes" element={<Compte/>}/>
                        <Route path="*" element={<Page404/>}/>
                    </Route> */}
                </Routes>

                <ToastContainer/>
            </BrowserRouter>
        </PersistGate>
    );
}

export default App;
