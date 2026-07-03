import React, { useEffect, useRef, useState } from 'react'
import Stepper from '../components/Stepper'
import PersonalInfo from '../components/stepItem/PersonalInfo'
import AcademiqueInfo from '../components/stepItem/AcademiqueInfo'
import OtherInfo from '../components/stepItem/OtherInfo'
import ConcoursInfo from '../components/stepItem/ConcoursInfo'
import Confirmation from '../components/stepItem/Confirmation'
import { useDispatch, useSelector } from 'react-redux'
import Loading from '../components/stepModal/Loading'
import { getSessionConcours } from '../api/routes/concours'
import { setCurrentSession } from '../app/modules/candidate'

function Candidate() {
  const step = useSelector((state) => state.stepper.step);
  const [isLoad, setLoadingState] = useState(false)
  const [sessionConcours, setSessionCouncours] = useState(null)
  const dispatch = useDispatch()
  const hasFetched = useRef(false)

  function fetchSessionConcours() {
    setLoadingState(true)
    getSessionConcours()
      .then(async function (res) {
        if (res.status === 200) {
          const data = await res.json()
          const { created_at, updated_at, ...session } = data
          setLoadingState(false)
          setSessionCouncours(session)
        } else {
          setLoadingState(false)
        }
      })
      .catch(() => {
        setLoadingState(false)
      })
  }

  // Fetch une seule fois au montage
  useEffect(() => {
    if (hasFetched.current) return
    hasFetched.current = true
    fetchSessionConcours()
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  // Dispatch uniquement quand la session est disponible
  useEffect(() => {
    if (sessionConcours && Object.keys(sessionConcours).length > 0) {
      dispatch(setCurrentSession(sessionConcours))
    }
  }, [sessionConcours, dispatch])

  const steppers = [
    <PersonalInfo setLoadingState={setLoadingState} />,
    <AcademiqueInfo setLoadingState={setLoadingState} />,
    <ConcoursInfo setLoadingState={setLoadingState} />,
    <OtherInfo setLoadingState={setLoadingState} />,
    <Confirmation setLoadingState={setLoadingState} />
  ]
  return (
    <div>
      <Stepper>
        {steppers[parseInt(step) - 1]}
      </Stepper>
      {isLoad && <Loading />}
    </div>
  )
}

export default Candidate