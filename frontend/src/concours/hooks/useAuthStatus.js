import {useEffect, useState, useRef} from 'react'
import {AUTH_CHECK_API} from '../api'
import {useDispatch} from 'react-redux'
import {push_candidate_info} from '../app/modules/candidate'
/* import { AUTH_STATUS_API } from '../lib/api' */

export const useAuthStatus = () => {
    const [loggedIn, setLoggedIn] = useState(false)
    const [checkingStatus, setCheckingStatus] = useState(true)
    const isMounted = useRef(true)
    const [token, setToken] = useState(localStorage.getItem('token'))
    const dispatch = useDispatch()

    // Synchronisation du token entre onglets
    useEffect(() => {
        const handleStorage = (event) => {
            if (event.key === 'token') {
                setToken(event.newValue)
            }
        }
        window.addEventListener('storage', handleStorage)
        return () => {
            window.removeEventListener('storage', handleStorage)
        }
    }, [])
    useEffect(() => {
        if (isMounted) {
            try {
                const {url, ...rest} = AUTH_CHECK_API

                fetch(url, {
                    ...rest,
                    headers: {
                        ...rest.headers,
                        Authorization: `Bearer ${token}`,
                    }
                }).then(async (res) => {
                    if (res.ok) {

                        const data = await res.json()
                        const {access_token, token_type, user, candidat} = data
                        dispatch(push_candidate_info(candidat))
                        setLoggedIn(true)
                        setToken(token);
                        localStorage.setItem('token', access_token)
                        localStorage.setItem('type_token', token_type)
                        localStorage.setItem('user', JSON.stringify(user))
                        localStorage.setItem('candidat', JSON.stringify(candidat))
                        setCheckingStatus(false)

                    } else {
                        setLoggedIn(false)
                        setCheckingStatus(false)
                        // Suppression des données d'authentification du localStorage
                        localStorage.removeItem('token');
                        localStorage.removeItem('type_token');
                        localStorage.removeItem('user');
                        localStorage.removeItem('user_type');
                        localStorage.removeItem('candidat');
                    }
                }).catch(function () {
                    setLoggedIn(false)
                    setCheckingStatus(false)
                })
                /* if (sessionStorage.getItem('token')) {
                  setLoggedIn(true)
                  //sessionStorage.setItem("user", JSON.stringify(user))
                  setCheckingStatus(false)
                  console.log(loggedIn)
                } else {
                  setCheckingStatus(false)
                } */
            } catch (error) {
                setLoggedIn(false)
                setCheckingStatus(false)
                // console.log(error)
            }
        }
        return () => {
            isMounted.current = false
        }
    }, [dispatch, isMounted, token])
    return {loggedIn, checkingStatus}
}

// Protected routes in v6
// https://stackoverflow.com/questions/65505665/protected-route-with-firebase

// Fix memory leak warning
// https://stackoverflow.com/questions/59780268/cleanup-memory-leaks-on-an-unmounted-component-in-react-hooks

