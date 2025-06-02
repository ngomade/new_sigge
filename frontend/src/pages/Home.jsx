import { useEffect, useState } from "react"
import Candidate from "../components/Candidate"
import HomeHeader from "../components/HomeHeader"
import Loading from "../components/stepModal/Loading"
import { getSessionConcours } from "../api/routes/concours"
import { useDispatch } from "react-redux"
import { setCurrentSession } from "../app/modules/candidate"

function Home() {
  const [isLoad,setLoadingState] = useState(false)
  const [sessionConcours,setSessionCouncours] = useState({})
  const dispatch = useDispatch()
  function fetchSessionConcours(){
    try {
      setLoadingState(true)
      getSessionConcours().then(async function(res){
        if(res.status ===200){
          const data = await res.json()
          const {created_at,updated_at,...session} = data
          setLoadingState(false)
          sessionStorage.setItem("session",JSON.stringify(data))
          return setSessionCouncours(session??JSON.parse(sessionStorage.getItem('session')))
        }
      })
    } catch (error) {
      setLoadingState(false)
    }
  }
  useEffect(() => {
    fetchSessionConcours()
    dispatch(setCurrentSession(sessionConcours))
    return () => {
      fetchSessionConcours()
      dispatch(setCurrentSession(sessionConcours))
    }
  }, [])
  
  return  (
    <div>
      <HomeHeader/>
      <Candidate isLoad={isLoad} setLoadingState={setLoadingState} />
      {isLoad&&<Loading></Loading> }
    </div>
  )
}

export default Home
