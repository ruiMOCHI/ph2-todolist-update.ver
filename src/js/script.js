// 追加処理、更新処理、削除処理を addEventListener で指定
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('js-create-todo').addEventListener('click', createTodo);

const completeButtons = document.querySelectorAll(".js-complete-todo");
  completeButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const todoId = button.getAttribute("data-id");
      updateTodo(todoId);
    });
  });  //このコードは、特定のクラス（js-complete-todo）を持つすべての要素に対して、クリックイベントを追加する処理を行っています。具体的には、次のような手順で動作しています。
       //document.querySelectorAll(".js-complete-todo") を使用して、クラスが js-complete-todo であるすべての要素を取得します。これらの要素は、ToDoを完了させるためのボタンと関連付けられています。
       //forEach() メソッドを使用して、取得したすべての要素に対してループします。
       //各要素に対して、クリックイベントのリスナーが追加されます。クリックされた際に、updateTodo 関数が呼び出されます。この関数は、引数として ToDo の ID を取ります。
       //クリックされたボタンから、それに関連付けられている ToDo の ID を取得するために、button.getAttribute("data-id") を使用します。data-id 属性には、ToDo の ID が格納されています。
       //このコードを理解すると、deleteButtons 関数を作成する際に、同様の手順を踏んで削除ボタンにクリックイベントを追加することができます。そのため、deleteButtons 関数も同様のパターンに従っています。削除ボタンがクリックされた際に、関連付けられている ToDo の ID を取得し、それを使用して削除処理を実行します。
       //このような手法を採用することで、異なるボタンや要素に同様の動作を実装する際に、コードの再利用性を高めることができます。

const deleteButtons = document.querySelectorAll(".js-delete-todo");
deleteButtons.forEach((button) => {
  button.addEventListener('click',  () => {
    const todoId = button.parentNode.getAttribute('data-id'); //parentNodeは指定する親要素が正しいか注意
    const parentNode = button.parentNode;
    deleteTodo(todoId, parentNode);
  });
});

});


//追加処理
async function createTodo() {
    const todoInput = document.getElementById('js-todo-text'); /*別のが入るかも？*/
    const todoText = todoInput.value;

    try {
        const response = await fetch("../admin/create/index.php", {
            method: "POST",
            headers: {
              "Content-type": "application/x-www-form-urlencoded",
            },
            body: `todo-text=${todoText}`
          });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error("Error from server: " + errorText);
        }

        const data = await response.json();
        addTodoElement(todoText, data.id);

        todoInput.value = '';
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

const updateTodoElement = (id, isCompleted) => {
  const todoElement = document.querySelector(`.js-todo[data-id="${id}"]`);

  if (todoElement) {
    const completeButton = todoElement.querySelector('.js-complete-todo');
    completeButton.textContent = isCompleted ? 'Undo' : 'Complete';
  }
}

//ステータス更新処理
async function updateTodo(id) {
  try {
    const response = await fetch("../admin/update/index.php", {
      method: 'POST',
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `toggle-id=${id}`
    })

    if(!response.ok) {
      const errorText = await response.text();
      throw new Error('Error from server: ' + errorText);
    }

    const data = await response.json();
    updateTodoElement(id, data.completed);

  } catch (error) {
    alert('Error: ' + error.message);
  }
}

// 削除処理
async function deleteTodo(id, element) {
    try {
      const response = await fetch("../admin/delete/index.php", {
        method: 'POST',
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `delete-id=${id}` /*delete-id? todo-id?*/
      });
  
      if (!response.ok) {
        const errorText = await response.text();
        throw new Error('Error from server: ' + errorText);
      }
  
      element.remove();
    } catch (error) {
      alert('Error: ' + error.message);
    }
  }