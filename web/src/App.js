import useWordpress from './hooks/useWordpress';
import { useLayoutEffect, useState } from 'react';

function App() {
  const { resources } = useWordpress()
  const [data, setData] = useState(null)

  useLayoutEffect(() => {
    async function getData() {
      const posts = await resources.core('posts', 'GET')
      setData(posts)
    }
    getData()
  }, [])

  return (
    <div id="wp-react-bridge" className="bg-transparent dark:bg-gray-900">
      <p className="text-black dark:text-white">This is a React app embedded in a WordPress Admin Dashboard screen.</p>
    </div>
  );
}

export default App;
