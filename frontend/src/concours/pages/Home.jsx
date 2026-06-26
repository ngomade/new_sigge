import { useEffect, useState, useRef } from "react"
import Candidate from "../components/Candidate"
import HomeHeader from "../components/HomeHeader"
import Loading from "../components/stepModal/Loading"
import { getSessionConcours } from "../api/routes/concours"
import { useDispatch } from "react-redux"
import { setCurrentSession } from "../app/modules/candidate"

function Home() {
    const [isLoad, setLoadingState] = useState(false)
    const [sessionConcours, setSessionCouncours] = useState(null) // ← null au lieu de {}
    const dispatch = useDispatch()
    const hasFetched = useRef(false) // ← garde-fou

    function fetchSessionConcours() {
        try {
            setLoadingState(true)
            getSessionConcours().then(async function (res) {
                if (res.status === 200) {
                    const data = await res.json()
                    const {created_at, updated_at, ...session} = data
                    setLoadingState(false)
                    sessionStorage.setItem("session", JSON.stringify(data))
                    setSessionCouncours(session ?? JSON.parse(sessionStorage.getItem('session')))
                } else {
                    setLoadingState(false)
                }
            })
        } catch (error) {
            setLoadingState(false)
        }
    }

    // Fetch unique au montage
    useEffect(() => {
        if (hasFetched.current) return
        hasFetched.current = true
        fetchSessionConcours()
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [])

    // Dispatch uniquement quand sessionConcours est une vraie valeur
    useEffect(() => {
        if (sessionConcours && Object.keys(sessionConcours).length > 0) {
            dispatch(setCurrentSession(sessionConcours))
        }
    }, [sessionConcours, dispatch])

    return (
        <div>
            <HomeHeader/>
            <Candidate isLoad={isLoad} setLoadingState={setLoadingState}/>
            {isLoad && <Loading/>}
        </div>
    )
}

export default Home
