import { Navigate, Outlet } from 'react-router-dom'
import { useAuthStatus } from '../hooks/useAuthStatus'
import Loading from './stepModal/Loading'

const PrivateRoute = () => {
  const { loggedIn, checkingStatus } = useAuthStatus()
  const location = window.location;

  if (checkingStatus) {
    return <Loading />
  }

  return loggedIn ? <Outlet /> : <Navigate to='/login' state={{ from: { pathname: location.pathname } }} replace />
}

export default PrivateRoute

