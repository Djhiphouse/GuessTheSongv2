package main

import (
	"fmt"
	"net/http"

	"github.com/gorilla/websocket"
)

var (
	upgrader   = websocket.Upgrader{
		CheckOrigin: func(r *http.Request) bool {
			return true
		},
	}
	connections = make(map[*websocket.Conn]bool)
)

func reader(conn *websocket.Conn) {
	defer func() {
		conn.Close()
		delete(connections, conn)
	}()

	for {
		messageType, p, err := conn.ReadMessage()
		if err != nil {
			fmt.Println("Error reading message:", err)
			break
		}
		fmt.Println("Received message:", string(p))
		broadcast(p)
		if err := conn.WriteMessage(messageType, p); err != nil {
			fmt.Println("Error writing message:", err)
			break
		}
	}
}

func broadcast(message []byte) {
	for conn := range connections {
		if err := conn.WriteMessage(websocket.TextMessage, message); err != nil {
			fmt.Println("Error broadcasting message to client:", err)
			conn.Close()
			delete(connections, conn)
		}
	}
}

func serveWs(w http.ResponseWriter, r *http.Request) {
	ws, err := upgrader.Upgrade(w, r, nil)
	if err != nil {
		fmt.Println("Error upgrading to WebSocket:", err)
		return
	}
	fmt.Println("New client connected")
	connections[ws] = true
	reader(ws)
	fmt.Println("Client disconnected")
}

func setupRoutes() {
	http.HandleFunc("/ws", serveWs)
}

func main() {
	fmt.Println("Chat App v0.01")
	setupRoutes()
	http.ListenAndServe(":8080", nil)
}
